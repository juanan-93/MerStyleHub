<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'icon_color',
        'action_url',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Tipos de notificación disponibles
     */
    const TYPE_APPOINTMENT_CANCELLED = 'appointment_cancelled';
    const TYPE_APPOINTMENT_CONFIRMED = 'appointment_confirmed';
    const TYPE_APPOINTMENT_REMINDER = 'appointment_reminder';
    const TYPE_QUESTIONNAIRE_ASSIGNED = 'questionnaire_assigned';
    const TYPE_QUESTIONNAIRE_REMINDER = 'questionnaire_reminder';
    const TYPE_QUESTIONNAIRE_COMPLETED = 'questionnaire_completed'; // Para admin
    const TYPE_APPOINTMENT_AVAILABLE = 'appointment_available';
    const TYPE_NEW_BOOKING = 'new_booking'; // Para admin: nueva reserva
    const TYPE_BOOKING_CANCELLED = 'booking_cancelled'; // Para admin: cancelación de reserva
    const TYPE_SYSTEM = 'system';
    const TYPE_WELCOME = 'welcome';

    /**
     * Obtener el ID del usuario (acepta User o int)
     */
    private static function resolveUserId(User|int $user): int
    {
        return $user instanceof User ? $user->id : $user;
    }

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificaciones leídas
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Marcar como leída
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Marcar como no leída
     */
    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Verificar si está leída
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Obtener el color CSS basado en icon_color
     */
    public function getColorClassAttribute(): string
    {
        return match($this->icon_color) {
            'success' => 'text-success',
            'danger' => 'text-danger',
            'warning' => 'text-warning',
            'info' => 'text-info',
            'primary' => 'text-primary-custom',
            default => 'text-secondary',
        };
    }

    /**
     * Obtener el color de fondo para el icono
     */
    public function getBgColorClassAttribute(): string
    {
        return match($this->icon_color) {
            'success' => 'bg-success-subtle',
            'danger' => 'bg-danger-subtle',
            'warning' => 'bg-warning-subtle',
            'info' => 'bg-info-subtle',
            'primary' => 'bg-primary-subtle',
            default => 'bg-secondary-subtle',
        };
    }

    /**
     * Formatear tiempo relativo
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->locale('es')->diffForHumans();
    }

    /**
     * Crear notificación de cita cancelada
     * @param User|int $user Usuario o ID del usuario
     * @param Appointment|array $appointment Cita o datos de la cita
     */
    public static function appointmentCancelled(User|int $user, Appointment|array $appointment): self
    {
        $appointmentData = $appointment instanceof Appointment 
            ? [
                'date' => $appointment->date->format('d/m/Y'),
                'time' => substr($appointment->start_time, 0, 5),
                'id' => $appointment->id,
            ]
            : $appointment;
            
        return self::create([
            'user_id' => self::resolveUserId($user),
            'type' => self::TYPE_APPOINTMENT_CANCELLED,
            'title' => 'Cita cancelada',
            'message' => "Tu cita del {$appointmentData['date']} a las {$appointmentData['time']} ha sido cancelada.",
            'icon' => 'ti-calendar-x',
            'icon_color' => 'danger',
            'action_url' => route('dashboardUser.index'),
            'data' => $appointmentData,
        ]);
    }

    /**
     * Crear notificación de cita confirmada
     * @param User|int $user Usuario o ID del usuario
     * @param Appointment|array $appointment Cita o datos de la cita
     */
    public static function appointmentConfirmed(User|int $user, Appointment|array $appointment): self
    {
        $appointmentData = $appointment instanceof Appointment 
            ? [
                'date' => $appointment->date->format('d/m/Y'),
                'time' => substr($appointment->start_time, 0, 5),
                'id' => $appointment->id,
            ]
            : $appointment;
            
        return self::create([
            'user_id' => self::resolveUserId($user),
            'type' => self::TYPE_APPOINTMENT_CONFIRMED,
            'title' => 'Cita confirmada',
            'message' => "Tu cita para el {$appointmentData['date']} a las {$appointmentData['time']} ha sido confirmada.",
            'icon' => 'ti-calendar-check',
            'icon_color' => 'success',
            'action_url' => route('dashboardUser.index'),
            'data' => $appointmentData,
        ]);
    }

    /**
     * Crear notificación de cuestionario asignado
     * @param User|int $user Usuario o ID del usuario
     * @param Questionnaire|array $questionnaire Cuestionario o datos del cuestionario
     */
    public static function questionnaireAssigned(User|int $user, Questionnaire|array $questionnaire): self
    {
        $questionnaireData = $questionnaire instanceof Questionnaire 
            ? [
                'title' => $questionnaire->title,
                'id' => $questionnaire->id,
            ]
            : $questionnaire;
            
        return self::create([
            'user_id' => self::resolveUserId($user),
            'type' => self::TYPE_QUESTIONNAIRE_ASSIGNED,
            'title' => 'Nuevo cuestionario disponible',
            'message' => "Tienes un nuevo cuestionario disponible: {$questionnaireData['title']}",
            'icon' => 'ti-clipboard-list',
            'icon_color' => 'info',
            'action_url' => route('dashboardUser.index') . '#perfil',
            'data' => $questionnaireData,
        ]);
    }

    /**
     * Crear notificación de citas disponibles asignadas
     * @param User|int $user Usuario o ID del usuario
     * @param string|array $data Título de la disponibilidad o datos completos
     */
    public static function appointmentAvailable(User|int $user, string|array $data): self
    {
        $availabilityData = is_string($data) ? ['title' => $data] : $data;
            
        return self::create([
            'user_id' => self::resolveUserId($user),
            'type' => self::TYPE_APPOINTMENT_AVAILABLE,
            'title' => 'Nuevas citas disponibles',
            'message' => "Tienes nuevas citas disponibles para reservar: {$availabilityData['title']}",
            'icon' => 'ti-calendar-plus',
            'icon_color' => 'primary',
            'action_url' => route('dashboardUser.index'),
            'data' => $availabilityData,
        ]);
    }

    /**
     * Crear notificación de bienvenida
     * @param User|int $user Usuario o ID del usuario
     * @param string|null $userName Nombre del usuario (opcional si se pasa User)
     */
    public static function welcome(User|int $user, ?string $userName = null): self
    {
        $userId = self::resolveUserId($user);
        $name = $userName ?? ($user instanceof User ? $user->name : User::find($userId)?->name ?? 'Usuario');
        
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_WELCOME,
            'title' => '¡Bienvenido/a a MerStyleHub!',
            'message' => 'Gracias por unirte. Explora tu panel para gestionar tus citas y servicios.',
            'icon' => 'ti-sparkles',
            'icon_color' => 'primary',
            'action_url' => route('dashboardUser.index'),
            'data' => ['user_name' => $name],
        ]);
    }

    /**
     * Crear notificación del sistema
     * @param User|int $user Usuario o ID del usuario
     */
    public static function system(User|int $user, string $title, string $message, ?string $actionUrl = null): self
    {
        return self::create([
            'user_id' => self::resolveUserId($user),
            'type' => self::TYPE_SYSTEM,
            'title' => $title,
            'message' => $message,
            'icon' => 'ti-info-circle',
            'icon_color' => 'info',
            'action_url' => $actionUrl,
        ]);
    }

    // ==========================================
    // NOTIFICACIONES PARA ADMINISTRADORES
    // ==========================================

    /**
     * Notificar a todos los admins sobre una nueva reserva
     * @param User|string $customer Usuario o nombre del cliente que reservó
     * @param Appointment|array $appointment Datos de la cita
     */
    public static function newBookingForAdmins(User|string $customer, Appointment|array $appointment): void
    {
        $customerName = $customer instanceof User ? $customer->name : $customer;
        
        $appointmentData = $appointment instanceof Appointment 
            ? [
                'date' => $appointment->date->format('d/m/Y'),
                'time' => substr($appointment->start_time, 0, 5),
                'id' => $appointment->id,
                'title' => $appointment->availability?->title ?? 'Cita',
            ]
            : $appointment;
        
        // Obtener todos los usuarios con rol admin
        $admins = User::role('admin')->get();
        
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'type' => self::TYPE_NEW_BOOKING,
                'title' => 'Nueva reserva de cita',
                'message' => "{$customerName} ha reservado una cita para el {$appointmentData['date']} a las {$appointmentData['time']}.",
                'icon' => 'ti-calendar-plus',
                'icon_color' => 'success',
                'action_url' => route('dashboardAdmin.index') . '?tab=appointments',
                'data' => array_merge($appointmentData, ['customer_name' => $customerName]),
            ]);
        }
    }

    /**
     * Notificar a todos los admins sobre una cancelación de reserva
     * @param User|string $customer Usuario o nombre del cliente que canceló
     * @param Appointment|array $appointment Datos de la cita
     */
    public static function bookingCancelledForAdmins(User|string $customer, Appointment|array $appointment): void
    {
        $customerName = $customer instanceof User ? $customer->name : $customer;
        
        $appointmentData = $appointment instanceof Appointment 
            ? [
                'date' => $appointment->date->format('d/m/Y'),
                'time' => substr($appointment->start_time, 0, 5),
                'id' => $appointment->id,
                'title' => $appointment->availability?->title ?? 'Cita',
            ]
            : $appointment;
        
        // Obtener todos los usuarios con rol admin
        $admins = User::role('admin')->get();
        
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'type' => self::TYPE_BOOKING_CANCELLED,
                'title' => 'Reserva cancelada',
                'message' => "{$customerName} ha cancelado su cita del {$appointmentData['date']} a las {$appointmentData['time']}.",
                'icon' => 'ti-calendar-x',
                'icon_color' => 'warning',
                'action_url' => route('dashboardAdmin.index') . '?tab=appointments',
                'data' => array_merge($appointmentData, ['customer_name' => $customerName]),
            ]);
        }
    }

    /**
     * Notificar a todos los admins que un usuario completó un cuestionario
     * @param User|int $customer Usuario que completó el cuestionario
     * @param Questionnaire|array $questionnaire Datos del cuestionario
     */
    public static function questionnaireCompletedForAdmins(User|int $customer, Questionnaire|array $questionnaire): void
    {
        $customerUser = $customer instanceof User ? $customer : User::find($customer);
        $customerName = $customerUser?->name ?? 'Usuario';
        
        $questionnaireData = $questionnaire instanceof Questionnaire 
            ? [
                'title' => $questionnaire->title,
                'id' => $questionnaire->id,
            ]
            : $questionnaire;
        
        // Obtener todos los usuarios con rol admin
        $admins = User::role('admin')->get();
        
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'type' => self::TYPE_QUESTIONNAIRE_COMPLETED,
                'title' => 'Cuestionario completado',
                'message' => "{$customerName} ha completado el cuestionario \"{$questionnaireData['title']}\".",
                'icon' => 'ti-clipboard-check',
                'icon_color' => 'info',
                'action_url' => route('questionnaire.user-responses', ['id' => $questionnaireData['id'], 'userId' => $customerUser?->id ?? 0]),
                'data' => array_merge($questionnaireData, ['customer_name' => $customerName, 'customer_id' => $customerUser?->id]),
            ]);
        }
    }
}
