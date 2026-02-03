<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario (vista completa)
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, unread, read
        
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }
        
        $notifications = $query->paginate(15);
        $unreadCount = Notification::where('user_id', Auth::id())->unread()->count();
        
        return view('notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Obtener notificaciones para el dropdown del header (AJAX)
     */
    public function getDropdownNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $unreadCount = Notification::where('user_id', Auth::id())->unread()->count();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'icon_color' => $notification->icon_color,
                    'color_class' => $notification->color_class,
                    'bg_color_class' => $notification->bg_color_class,
                    'action_url' => $notification->action_url,
                    'is_read' => $notification->isRead(),
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->format('d/m/Y H:i'),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->unread()
            ->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada',
        ]);
    }

    /**
     * Eliminar todas las notificaciones leídas
     */
    public function destroyAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->read()
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notificaciones leídas eliminadas',
        ]);
    }

    /**
     * Obtener contador de notificaciones no leídas (para actualización en tiempo real)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())->unread()->count();
        
        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
