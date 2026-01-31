<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public string $formattedDate;
    public string $formattedTime;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
        
        // Formatear fecha y hora para mostrar en el email
        Carbon::setLocale('es');
        $this->formattedDate = Carbon::parse($appointment->date)->translatedFormat('l, d \d\e F \d\e Y');
        $this->formattedTime = Carbon::parse($appointment->start_time)->format('H:i') . ' - ' . Carbon::parse($appointment->end_time)->format('H:i');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✨ Confirmación de tu cita - MerStyleHub',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
