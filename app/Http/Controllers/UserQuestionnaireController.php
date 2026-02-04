<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\QuestionnaireUser;
use App\Models\UserQuestionnaireResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserQuestionnaireController extends Controller
{
    /**
     * Mostrar listado de cuestionarios asignados al usuario
     */
    public function index()
    {
        $user = Auth::user();
        
        $questionnaires = $user->questionnaires()
            ->withPivot(['status', 'assigned_at', 'completed_at'])
            ->orderByRaw("CASE WHEN questionnaire_user.status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('questionnaire_user.assigned_at', 'desc')
            ->get();
        
        return view('user-questionnaire.index', compact('questionnaires'));
    }

    /**
     * Mostrar un cuestionario para responder
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Verificar que el cuestionario esté asignado al usuario
        $questionnaire = Questionnaire::with([
            'questions' => function($query) {
                $query->orderBy('order');
            },
            'questions.options' => function($query) {
                $query->orderBy('order');
            }
        ])
        ->whereHas('users', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->findOrFail($id);
        
        // Obtener la asignación
        $assignment = QuestionnaireUser::where('questionnaire_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$assignment) {
            abort(404);
        }
        
        // Obtener respuestas existentes (si hay)
        $existingResponses = UserQuestionnaireResponse::where('questionnaire_user_id', $assignment->id)
            ->get()
            ->keyBy('question_id');
        
        return view('user-questionnaire.show', compact('questionnaire', 'assignment', 'existingResponses'));
    }

    /**
     * Guardar las respuestas del cuestionario
     */
    public function store(Request $request, $id)
    {
        \Log::info('=== STORE METHOD CALLED ===');
        \Log::info('Cuestionario ID: ' . $id);
        
        $user = Auth::user();
        
        \Log::info('Usuario autenticado: ' . ($user ? $user->id : 'NO'));
        
        // Verificar que el cuestionario esté asignado al usuario
        $questionnaire = Questionnaire::with('questions')
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->findOrFail($id);
        
        // Obtener la asignación
        $assignment = QuestionnaireUser::where('questionnaire_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$assignment) {
            abort(404);
        }
        
        // Verificar si ya está completado
        if ($assignment->isCompleted()) {
            return redirect()
                ->route('user-questionnaire.show', $id)
                ->with('info', 'Este cuestionario ya ha sido completado.');
        }

        try {
            DB::beginTransaction();
            
            \Log::info('=== INICIANDO GUARDADO DE RESPUESTAS ===');
            \Log::info('Usuario ID: ' . $user->id);
            \Log::info('Cuestionario ID: ' . $questionnaire->id);
            \Log::info('Assignment ID: ' . $assignment->id);
            \Log::info('Total preguntas: ' . $questionnaire->questions->count());
            \Log::info('Request data: ', $request->all());
            \Log::info('Request files: ', $request->allFiles());

            // Procesar cada respuesta
            foreach ($questionnaire->questions as $question) {
                $responseKey = 'question_' . $question->id;
                
                // Para archivos, verificar $request->file en lugar de $request->input
                if ($question->type === 'file') {
                    $files = $request->file($responseKey);
                    
                    if ($files && (is_array($files) ? count($files) > 0 : true)) {
                        // Asegurar que sea array
                        $filesArray = is_array($files) ? $files : [$files];
                        $filePaths = [];
                        
                        foreach ($filesArray as $file) {
                            if (!$file || !$file->isValid()) continue;
                            
                            // Validar tamaño (5MB máx)
                            if ($file->getSize() > 5 * 1024 * 1024) {
                                throw new \Exception("El archivo \"{$file->getClientOriginalName()}\" excede el tamaño máximo de 5MB.");
                            }
                            
                            // Guardar archivo
                            $path = $file->store('questionnaire-responses/' . $user->id, 'public');
                            $filePaths[] = [
                                'path' => $path,
                                'name' => $file->getClientOriginalName(),
                                'size' => $file->getSize(),
                                'mime' => $file->getMimeType()
                            ];
                        }
                        
                        if (count($filePaths) > 0) {
                            UserQuestionnaireResponse::updateOrCreate(
                                [
                                    'questionnaire_user_id' => $assignment->id,
                                    'question_id' => $question->id,
                                ],
                                [
                                    'questionnaire_user_id' => $assignment->id,
                                    'question_id' => $question->id,
                                    'question_option_id' => null,
                                    'text_response' => json_encode($filePaths)
                                ]
                            );
                        } elseif ($question->required) {
                            throw new \Exception("La pregunta \"{$question->text}\" requiere al menos un archivo.");
                        }
                    } elseif ($question->required) {
                        throw new \Exception("La pregunta \"{$question->text}\" requiere al menos un archivo.");
                    }
                    continue;
                }
                
                $responseValue = $request->input($responseKey);
                
                // Saltar si no hay respuesta y no es requerida
                if (empty($responseValue) && !$question->required) {
                    continue;
                }
                
                // Verificar preguntas requeridas
                if ($question->required && empty($responseValue)) {
                    throw new \Exception("La pregunta \"{$question->text}\" es obligatoria.");
                }
                
                // Crear o actualizar respuesta
                $responseData = [
                    'questionnaire_user_id' => $assignment->id,
                    'question_id' => $question->id,
                ];
                
                if (in_array($question->type, ['test', 'select'])) {
                    // Si es "other" (opción otro), guardar como texto
                    if ($responseValue === 'other') {
                        $otherText = $request->input('question_' . $question->id . '_other');
                        $responseData['question_option_id'] = null;
                        $responseData['text_response'] = $otherText ?? '';
                    } else {
                        $responseData['question_option_id'] = $responseValue;
                        $responseData['text_response'] = null;
                    }
                } elseif ($question->type === 'info') {
                    // Para tipo info (informativo), guardar confirmación en text_response
                    // El valor "read" indica que el usuario leyó la información
                    $responseData['question_option_id'] = null;
                    if (is_array($responseValue)) {
                        $responseData['text_response'] = json_encode($responseValue);
                    } else {
                        $responseData['text_response'] = $responseValue; // "read" o cualquier confirmación
                    }
                } else {
                    // Tipo texto
                    $responseData['question_option_id'] = null;
                    $responseData['text_response'] = $responseValue;
                }
                
                // Usar updateOrCreate para permitir ediciones
                \Log::info("Guardando respuesta para pregunta {$question->id}: ", $responseData);
                
                $savedResponse = UserQuestionnaireResponse::updateOrCreate(
                    [
                        'questionnaire_user_id' => $assignment->id,
                        'question_id' => $question->id,
                    ],
                    $responseData
                );
                
                \Log::info("Respuesta guardada ID: " . $savedResponse->id);
            }
            
            \Log::info('=== TODAS LAS RESPUESTAS GUARDADAS, MARCANDO COMO COMPLETADO ===');
            
            // Marcar como completado
            $assignment->markAsCompleted();
            
            // Notificar a los administradores
            Notification::questionnaireCompletedForAdmins($user, $questionnaire);
            
            DB::commit();

            return redirect()
                ->route('user-questionnaire.index')
                ->with('success', '¡Cuestionario completado correctamente! Gracias por tus respuestas.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al guardar las respuestas: ' . $e->getMessage());
        }
    }

    /**
     * Ver respuestas de un cuestionario completado (solo lectura)
     */
    public function viewResponses($id)
    {
        $user = Auth::user();
        
        // Verificar que el cuestionario esté asignado al usuario
        $questionnaire = Questionnaire::with(['questions.options'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->findOrFail($id);
        
        // Obtener la asignación
        $assignment = QuestionnaireUser::where('questionnaire_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$assignment || !$assignment->isCompleted()) {
            return redirect()
                ->route('user-questionnaire.show', $id)
                ->with('info', 'Debes completar el cuestionario primero.');
        }
        
        // Obtener respuestas
        $responses = UserQuestionnaireResponse::where('questionnaire_user_id', $assignment->id)
            ->with(['question', 'selectedOption'])
            ->get()
            ->keyBy('question_id');
        
        return view('user-questionnaire.responses', compact('questionnaire', 'assignment', 'responses'));
    }
}
