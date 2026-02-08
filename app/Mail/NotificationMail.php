<?php

namespace App\Mail;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Notification $notification;
    public User $user;
    public string $emoji;
    public string $accentColor;
    public string $bgColor;

    /**
     * Mapeo de tipos de notificación a emojis y colores
     */
    private const TYPE_STYLES = [
        Notification::TYPE_APPOINTMENT_CANCELLED => ['emoji' => '❌', 'accent' => '#dc3545', 'bg' => '#FFF5F5'],
        Notification::TYPE_APPOINTMENT_CONFIRMED => ['emoji' => '✅', 'accent' => '#28a745', 'bg' => '#F0FFF4'],
        Notification::TYPE_APPOINTMENT_REMINDER => ['emoji' => '⏰', 'accent' => '#ffc107', 'bg' => '#FFF9E6'],
        Notification::TYPE_APPOINTMENT_MOVED => ['emoji' => '🔄', 'accent' => '#17a2b8', 'bg' => '#E3F6F8'],
        Notification::TYPE_APPOINTMENT_AVAILABLE => ['emoji' => '📅', 'accent' => '#A08A7A', 'bg' => '#F8F6F3'],
        Notification::TYPE_QUESTIONNAIRE_ASSIGNED => ['emoji' => '📋', 'accent' => '#17a2b8', 'bg' => '#E3F6F8'],
        Notification::TYPE_QUESTIONNAIRE_REMINDER => ['emoji' => '📝', 'accent' => '#ffc107', 'bg' => '#FFF9E6'],
        Notification::TYPE_QUESTIONNAIRE_COMPLETED => ['emoji' => '✏️', 'accent' => '#17a2b8', 'bg' => '#E3F6F8'],
        Notification::TYPE_NEW_MESSAGE => ['emoji' => '💬', 'accent' => '#17a2b8', 'bg' => '#E3F6F8'],
        Notification::TYPE_NEW_MESSAGE_ADMIN => ['emoji' => '💬', 'accent' => '#17a2b8', 'bg' => '#E3F6F8'],
        Notification::TYPE_DOCUMENT_UPLOADED => ['emoji' => '📄', 'accent' => '#28a745', 'bg' => '#F0FFF4'],
        Notification::TYPE_NEW_BOOKING => ['emoji' => '🆕', 'accent' => '#28a745', 'bg' => '#F0FFF4'],
        Notification::TYPE_BOOKING_CANCELLED => ['emoji' => '🚫', 'accent' => '#ffc107', 'bg' => '#FFF9E6'],
        Notification::TYPE_WELCOME => ['emoji' => '✨', 'accent' => '#A08A7A', 'bg' => '#F8F6F3'],
        Notification::TYPE_SYSTEM => ['emoji' => 'ℹ️', 'accent' => '#6c757d', 'bg' => '#F8F9FA'],
    ];

    /**
     * Mapeo de tipos a asuntos de email
     */
    private const TYPE_SUBJECTS = [
        Notification::TYPE_APPOINTMENT_CANCELLED => '❌ Cita cancelada',
        Notification::TYPE_APPOINTMENT_CONFIRMED => '✅ Cita confirmada',
        Notification::TYPE_APPOINTMENT_REMINDER => '⏰ Recordatorio de cita',
        Notification::TYPE_APPOINTMENT_MOVED => '🔄 Tu cita ha sido reubicada',
        Notification::TYPE_APPOINTMENT_AVAILABLE => '📅 Nuevas citas disponibles',
        Notification::TYPE_QUESTIONNAIRE_ASSIGNED => '📋 Nuevo cuestionario disponible',
        Notification::TYPE_QUESTIONNAIRE_REMINDER => '📝 Recordatorio de cuestionario',
        Notification::TYPE_QUESTIONNAIRE_COMPLETED => '✏️ Cuestionario completado',
        Notification::TYPE_NEW_MESSAGE => '💬 Tienes un nuevo mensaje',
        Notification::TYPE_NEW_MESSAGE_ADMIN => '💬 Nuevo mensaje de cliente',
        Notification::TYPE_DOCUMENT_UPLOADED => '📄 Nuevo documento disponible',
        Notification::TYPE_NEW_BOOKING => '🆕 Nueva reserva de cita',
        Notification::TYPE_BOOKING_CANCELLED => '🚫 Reserva cancelada',
        Notification::TYPE_WELCOME => '✨ ¡Bienvenido/a a MerStyleHub!',
        Notification::TYPE_SYSTEM => 'ℹ️ Notificación del sistema',
    ];

    public function __construct(Notification $notification, User $user)
    {
        $this->notification = $notification;
        $this->user = $user;

        $style = self::TYPE_STYLES[$notification->type] ?? self::TYPE_STYLES[Notification::TYPE_SYSTEM];
        $this->emoji = $style['emoji'];
        $this->accentColor = $style['accent'];
        $this->bgColor = $style['bg'];
    }

    public function envelope(): Envelope
    {
        $subject = self::TYPE_SUBJECTS[$this->notification->type] 
            ?? "🔔 {$this->notification->title}";

        return new Envelope(
            subject: "{$subject} - MerStyleHub",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
