<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatUserController extends Controller
{
    /**
     * Lista de conversaciones del customer (normalmente solo 1 con el admin)
     */
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::where('customer_id', $user->id)
            ->with(['admin', 'lastMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        // Si tiene una sola conversación, ir directamente a ella
        if ($conversations->count() === 1) {
            return redirect()->route('chat-user.show', $conversations->first()->id);
        }

        // Si no tiene conversaciones, mostrar vista con opción de iniciar
        if ($conversations->isEmpty()) {
            // Buscar admin para iniciar conversación
            $admin = User::role('admin')->first();
            if ($admin) {
                $conversation = Conversation::getOrCreate($admin->id, $user->id);
                return redirect()->route('chat-user.show', $conversation->id);
            }
        }

        return view('chatUser.index', compact('conversations'));
    }

    /**
     * Ver una conversación
     */
    public function show($conversationId)
    {
        $user = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where('customer_id', $user->id)
            ->with(['admin', 'messages.sender'])
            ->firstOrFail();

        // Marcar mensajes como leídos
        $conversation->markAsReadFor($user->id);

        return view('chatUser.show', compact('conversation'));
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

        $user = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where('customer_id', $user->id)
            ->firstOrFail();

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
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

        // Enviar notificación al admin
        Notification::newMessageForAdmin($conversation->admin_id, $user, $conversation);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return redirect()->route('chat-user.show', $conversationId);
    }

    /**
     * Obtener mensajes nuevos (polling AJAX)
     */
    public function getNewMessages(Request $request, $conversationId)
    {
        $user = Auth::user();
        $lastMessageId = $request->input('last_message_id', 0);

        $conversation = Conversation::where('id', $conversationId)
            ->where('customer_id', $user->id)
            ->firstOrFail();

        $messages = $conversation->messages()
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        // Marcar como leídos los mensajes del admin
        $conversation->markAsReadFor($user->id);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Obtener total de mensajes no leídos para el customer
     */
    public function getUnreadTotal()
    {
        $user = Auth::user();

        $total = Message::whereHas('conversation', function ($q) use ($user) {
            $q->where('customer_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->whereNull('read_at')
        ->count();

        return response()->json(['total' => $total]);
    }

    /**
     * Eliminar un mensaje propio
     */
    public function deleteMessage($conversationId, $messageId)
    {
        $user = Auth::user();

        $message = Message::where('id', $messageId)
            ->where('sender_id', $user->id)
            ->whereHas('conversation', function ($q) use ($user, $conversationId) {
                $q->where('id', $conversationId)->where('customer_id', $user->id);
            })
            ->firstOrFail();

        $message->delete();

        return response()->json(['success' => true]);
    }
}
