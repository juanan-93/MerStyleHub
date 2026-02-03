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
        $user = Auth::user();
        
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

            // Procesar cada respuesta
            foreach ($questionnaire->questions as $question) {
                $responseKey = 'question_' . $question->id;
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
                    // Para tipo info (informativo), puede ser múltiple o single
                    if (is_array($responseValue)) {
                        // Múltiples opciones seleccionadas - guardar como JSON
                        $responseData['question_option_id'] = null;
                        $responseData['text_response'] = json_encode($responseValue);
                    } else {
                        $responseData['question_option_id'] = $responseValue;
                        $responseData['text_response'] = null;
                    }
                } else {
                    // Tipo texto o file
                    $responseData['question_option_id'] = null;
                    $responseData['text_response'] = $responseValue;
                }
                
                // Usar updateOrCreate para permitir ediciones
                UserQuestionnaireResponse::updateOrCreate(
                    [
                        'questionnaire_user_id' => $assignment->id,
                        'question_id' => $question->id,
                    ],
                    $responseData
                );
            }
            
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
