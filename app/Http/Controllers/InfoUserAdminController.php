<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\QuestionnaireUser;
use App\Models\CustomerDocument;
use Illuminate\Support\Facades\Storage;

class InfoUserAdminController extends Controller
{
    public function show($userId)
    {
        $user = User::with([
            'customerProfile.product', 
            'customerProfile.colorimetry',
            'customerProfile.documents'
        ])->findOrFail($userId);
        $profile = $user->customerProfile;
        
        // Obtener cuestionarios asignados con su información
        $assignedQuestionnaires = QuestionnaireUser::with(['questionnaire'])
            ->where('user_id', $userId)
            ->orderBy('assigned_at', 'desc')
            ->get();
        
        return view('info_user_admin.index', compact('user', 'profile', 'assignedQuestionnaires'));
    }

    public function showQuestionnaireResponses($userId, $questionnaireUserId)
    {
        $user = User::findOrFail($userId);
        
        $questionnaireUser = QuestionnaireUser::with([
            'questionnaire.questions.options',
            'responses.question.options',
            'responses.selectedOption'
        ])->findOrFail($questionnaireUserId);
        
        // Verificar que el cuestionario pertenece al usuario
        if ($questionnaireUser->user_id != $userId) {
            abort(403, 'No autorizado');
        }
        
        return view('info_user_admin.details.responseQuestionnaire', compact('user', 'questionnaireUser'));
    }

    public function uploadDocument(Request $request, $userId)
    {
        $request->validate([
            'document' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        $user = User::findOrFail($userId);
        $profile = $user->customerProfile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene perfil de cliente'
            ], 404);
        }

        try {
            $file = $request->file('document');
            
            // Generar nombre único
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Guardar en storage/app/public/customer_documents/{profile_id}
            $filePath = $file->storeAs(
                'customer_documents/' . $profile->id,
                $fileName,
                'public'
            );

            // Crear registro en base de datos
            $document = CustomerDocument::create([
                'customer_profile_id' => $profile->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento subido correctamente',
                'document' => [
                    'id' => $document->id,
                    'file_name' => $document->file_name,
                    'file_url' => Storage::url($document->file_path),
                    'formatted_size' => $document->formatted_size,
                    'created_at' => $document->created_at->format('d/m/Y H:i')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument($userId, $documentId)
    {
        $user = User::findOrFail($userId);
        $profile = $user->customerProfile;

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene perfil de cliente'
            ], 404);
        }

        $document = CustomerDocument::where('customer_profile_id', $profile->id)
            ->where('id', $documentId)
            ->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado'
            ], 404);
        }

        try {
            $document->delete(); // El modelo se encarga de eliminar el archivo
            
            return response()->json([
                'success' => true,
                'message' => 'Documento eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el documento: ' . $e->getMessage()
            ], 500);
        }
    }
}
