<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use App\Models\Notification;
use App\Http\Requests\StoreQuestionnaireRequest;
use App\Http\Requests\UpdateQuestionnaireRequest;
use App\Http\Requests\AssignQuestionnaireRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    /**
     * Mostrar listado de cuestionarios
     */
    public function index()
    {
        $questionnaires = Questionnaire::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('questionnaire.index', compact('questionnaires'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('questionnaire.create');
    }

    /**
     * Guardar nuevo cuestionario
     */
    public function store(StoreQuestionnaireRequest $request)
    {
        try {
            DB::beginTransaction();

            // Crear el cuestionario
            $questionnaire = Questionnaire::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Crear las preguntas
            foreach ($request->questions as $order => $questionData) {
                $question = $questionnaire->questions()->create([
                    'text' => $questionData['text'],
                    'type' => $questionData['type'],
                    'order' => $order,
                    'required' => $questionData['required'] ?? true,
                    'allow_other_option' => isset($questionData['allow_other_option']) ? true : false,
                ]);

                // Crear opciones si es pregunta tipo test, select o info
                if (in_array($questionData['type'], ['test', 'select', 'info']) && !empty($questionData['options'])) {
                    foreach ($questionData['options'] as $optionOrder => $optionText) {
                        if (!empty(trim($optionText))) {
                            $question->options()->create([
                                'text' => $optionText,
                                'order' => $optionOrder,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('questionnaire.index')
                ->with('success', 'Cuestionario creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el cuestionario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $questionnaire = Questionnaire::with(['questions.options'])->findOrFail($id);

        return view('questionnaire.edit', compact('questionnaire'));
    }

    /**
     * Actualizar cuestionario existente
     */
    public function update(UpdateQuestionnaireRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $questionnaire = Questionnaire::findOrFail($id);

            // Actualizar datos del cuestionario
            $questionnaire->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Obtener IDs de preguntas existentes
            $existingQuestionIds = $questionnaire->questions()->pluck('id')->toArray();
            $updatedQuestionIds = [];

            // Procesar preguntas
            $orderIndex = 0;
            foreach ($request->questions as $key => $questionData) {
                if (!empty($questionData['id'])) {
                    // Actualizar pregunta existente
                    $question = Question::find($questionData['id']);
                    if ($question && $question->questionnaire_id == $questionnaire->id) {
                        $question->update([
                            'text' => $questionData['text'],
                            'type' => $questionData['type'],
                            'order' => $orderIndex,
                            'required' => $questionData['required'] ?? true,
                            'allow_other_option' => isset($questionData['allow_other_option']) ? true : false,
                        ]);
                        $updatedQuestionIds[] = $question->id;
                    }
                } else {
                    // Crear nueva pregunta
                    $question = $questionnaire->questions()->create([
                        'text' => $questionData['text'],
                        'type' => $questionData['type'],
                        'order' => $orderIndex,
                        'required' => $questionData['required'] ?? true,
                        'allow_other_option' => isset($questionData['allow_other_option']) ? true : false,
                    ]);
                    $updatedQuestionIds[] = $question->id;
                }
                
                $orderIndex++;

                // Procesar opciones si es tipo test, select o info
                if (in_array($questionData['type'], ['test', 'select', 'info'])) {
                    $existingOptionIds = $question->options()->pluck('id')->toArray();
                    $updatedOptionIds = [];

                    if (!empty($questionData['options'])) {
                        foreach ($questionData['options'] as $optionOrder => $optionData) {
                            $optionText = is_array($optionData) ? $optionData['text'] : $optionData;
                            $optionId = is_array($optionData) ? ($optionData['id'] ?? null) : null;

                            if (empty(trim($optionText))) continue;

                            if ($optionId && in_array($optionId, $existingOptionIds)) {
                                // Actualizar opción existente
                                QuestionOption::where('id', $optionId)->update([
                                    'text' => $optionText,
                                    'order' => $optionOrder,
                                ]);
                                $updatedOptionIds[] = $optionId;
                            } else {
                                // Crear nueva opción
                                $option = $question->options()->create([
                                    'text' => $optionText,
                                    'order' => $optionOrder,
                                ]);
                                $updatedOptionIds[] = $option->id;
                            }
                        }
                    }

                    // Eliminar opciones que ya no existen
                    $optionsToDelete = array_diff($existingOptionIds, $updatedOptionIds);
                    QuestionOption::whereIn('id', $optionsToDelete)->delete();
                } else {
                    // Si cambió de tipo test/select/info a texto o file, eliminar opciones
                    $question->options()->delete();
                }
            }

            // Eliminar preguntas que ya no existen
            $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);
            Question::whereIn('id', $questionsToDelete)->delete();

            DB::commit();

            return redirect()
                ->route('questionnaire.index')
                ->with('success', 'Cuestionario actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el cuestionario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cuestionario
     */
    public function destroy($id)
    {
        try {
            $questionnaire = Questionnaire::findOrFail($id);
            
            // Las preguntas y opciones se eliminarán automáticamente por cascade
            $questionnaire->delete();

            return redirect()
                ->route('questionnaire.index')
                ->with('success', 'Cuestionario eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el cuestionario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar vista para asignar cuestionario a usuarios
     */
    public function showAssign($id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        // Obtener usuarios con rol customer que no tienen asignado este cuestionario
        $assignedUserIds = $questionnaire->users()->pluck('users.id')->toArray();
        
        $availableUsers = User::role('customer')
            ->whereNotIn('id', $assignedUserIds)
            ->orderBy('name')
            ->get();

        $assignedUsers = $questionnaire->users()
            ->withPivot(['status', 'assigned_at', 'completed_at'])
            ->orderBy('pivot_assigned_at', 'desc')
            ->get();

        return view('questionnaire.assign', compact('questionnaire', 'availableUsers', 'assignedUsers'));
    }

    /**
     * Asignar cuestionario a usuarios
     */
    public function assign(AssignQuestionnaireRequest $request, $id)
    {
        try {
            $questionnaire = Questionnaire::findOrFail($id);

            // Asignar cuestionario a los usuarios seleccionados
            foreach ($request->user_ids as $userId) {
                // Verificar si ya está asignado
                if (!$questionnaire->users()->where('user_id', $userId)->exists()) {
                    $questionnaire->users()->attach($userId, [
                        'status' => 'pending',
                        'assigned_at' => now(),
                    ]);
                    
                    // Crear notificación para el usuario
                    Notification::questionnaireAssigned($userId, $questionnaire);
                }
            }

            return redirect()
                ->route('questionnaire.assign', $id)
                ->with('success', 'Cuestionario asignado correctamente a los usuarios seleccionados.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al asignar el cuestionario: ' . $e->getMessage());
        }
    }

    /**
     * Quitar asignación de cuestionario a un usuario
     */
    public function unassign($id, $userId)
    {
        try {
            $questionnaire = Questionnaire::findOrFail($id);
            $questionnaire->users()->detach($userId);

            return redirect()
                ->route('questionnaire.assign', $id)
                ->with('success', 'Asignación eliminada correctamente.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar la asignación: ' . $e->getMessage());
        }
    }

    /**
     * Ver respuestas de un cuestionario
     */
    public function responses($id)
    {
        $questionnaire = Questionnaire::with([
            'questions.options',
            'users' => function ($query) {
                $query->wherePivot('status', 'completed')
                    ->withPivot(['status', 'assigned_at', 'completed_at']);
            }
        ])->findOrFail($id);

        return view('questionnaire.responses', compact('questionnaire'));
    }

    /**
     * Ver respuestas de un usuario específico
     */
    public function userResponses($id, $userId)
    {
        $questionnaire = Questionnaire::with(['questions.options'])->findOrFail($id);
        $user = User::findOrFail($userId);
        
        $assignment = $questionnaire->users()
            ->where('user_id', $userId)
            ->withPivot(['status', 'assigned_at', 'completed_at'])
            ->first();

        if (!$assignment) {
            return redirect()
                ->back()
                ->with('error', 'El usuario no tiene asignado este cuestionario.');
        }

        // Obtener las respuestas del usuario
        $responses = \App\Models\UserQuestionnaireResponse::where('questionnaire_user_id', $assignment->pivot->id)
            ->with(['question', 'selectedOption'])
            ->get()
            ->keyBy('question_id');

        return view('questionnaire.user-responses', compact('questionnaire', 'user', 'assignment', 'responses'));
    }
}
