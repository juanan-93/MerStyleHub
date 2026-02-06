<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatAdminController extends Controller
{
    /**
     * Lista de conversaciones del admin
     */
    public function index()
    {
        $admin = Auth::user();

        $conversations = Conversation::where('admin_id', $admin->id)
            ->with(['customer.customerProfile', 'lastMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        // Usuarios customer que aún no tienen conversación con este admin
        $customersWithoutChat = User::role('customer')
            ->whereNotIn('id', $conversations->pluck('customer_id'))
            ->get();

        return view('chatAdmin.index', compact('conversations', 'customersWithoutChat'));
    }

    /**
     * Ver/abrir una conversación específica
     */
    public function show($conversationId)
    {
        $admin = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where('admin_id', $admin->id)
            ->with(['customer.customerProfile', 'messages.sender'])
            ->firstOrFail();

        // Marcar mensajes como leídos
        $conversation->markAsReadFor($admin->id);

        // Cargar lista de conversaciones para el sidebar
        $conversations = Conversation::where('admin_id', $admin->id)
            ->with(['customer.customerProfile', 'lastMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('chatAdmin.show', compact('conversation', 'conversations'));
    }

    /**
     * Iniciar nueva conversación con un customer
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
        ]);

        $admin = Auth::user();
        $conversation = Conversation::getOrCreate($admin->id, $request->customer_id);

        return redirect()->route('chat-admin.show', $conversation->id);
    }

    /**
     * Enviar mensaje
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'body' => 'required_without:attachment|nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240', // 10MB máximo
        ]);

        $admin = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where('admin_id', $admin->id)
            ->firstOrFail();

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'body' => $request->body,
        ];

        // Procesar archivo adjunto
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat_attachments/' . $conversation->id, 'public');

            $messageData['attachment_path'] = $path;
            $messageData['attachment_name'] = $file->getClientOriginalName();
            $messageData['attachment_type'] = $file->getMimeType();
            $messageData['attachment_size'] = $file->getSize();
        }

        $message = Message::create($messageData);

        // Actualizar timestamp de última actividad
        $conversation->update(['last_message_at' => now()]);

        // Enviar notificación al customer
        Notification::newMessage($conversation->customer_id, $admin, $conversation);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return redirect()->route('chat-admin.show', $conversationId);
    }

    /**
     * Obtener mensajes nuevos (polling AJAX)
     */
    public function getNewMessages(Request $request, $conversationId)
    {
        $admin = Auth::user();
        $lastMessageId = $request->input('last_message_id', 0);

        $conversation = Conversation::where('id', $conversationId)
            ->where('admin_id', $admin->id)
            ->firstOrFail();

        $messages = $conversation->messages()
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        // Marcar como leídos los mensajes del customer
        $conversation->markAsReadFor($admin->id);

        return response()->json([
            'messages' => $messages,
            'unread_counts' => $this->getUnreadCounts($admin->id),
        ]);
    }

    /**
     * Obtener conteo de no leídos por conversación
     */
    private function getUnreadCounts(int $adminId): array
    {
        $conversations = Conversation::where('admin_id', $adminId)->get();
        $counts = [];
        foreach ($conversations as $conv) {
            $counts[$conv->id] = $conv->unreadMessagesFor($adminId);
        }
        return $counts;
    }

    /**
     * Obtener total de mensajes no leídos para el admin
     */
    public function getUnreadTotal()
    {
        $admin = Auth::user();

        $total = Message::whereHas('conversation', function ($q) use ($admin) {
            $q->where('admin_id', $admin->id);
        })
        ->where('sender_id', '!=', $admin->id)
        ->whereNull('read_at')
        ->count();

        return response()->json(['total' => $total]);
    }

    /**
     * Eliminar un mensaje propio
     */
    public function deleteMessage($conversationId, $messageId)
    {
        $admin = Auth::user();

        $message = Message::where('id', $messageId)
            ->where('sender_id', $admin->id)
            ->whereHas('conversation', function ($q) use ($admin, $conversationId) {
                $q->where('id', $conversationId)->where('admin_id', $admin->id);
            })
            ->firstOrFail();

        $message->delete();

        return response()->json(['success' => true]);
    }
}
