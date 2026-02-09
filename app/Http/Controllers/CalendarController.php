<?php

namespace App\Http\Controllers;

use App\Models\AppointmentAvailability;
use App\Models\Appointment;
use App\Mail\AppointmentConfirmation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Obtiene fechas con disponibilidad (para pintar dots en calendario)
     * Con caché de 5 minutos para reducir consultas
     * Solo muestra fechas con categoría "standard"
     */
    public function getAvailableDates()
    {
        $cacheKey = 'available_dates_standard_' . now()->format('Y-m-d');
        
        $availableDates = Cache::remember($cacheKey, 300, function () {
            return AppointmentAvailability::where('date', '>=', now()->toDateString())
                ->where('category', 'standard')
                ->select('date')
                ->distinct()
                ->orderBy('date')
                ->pluck('date')
                ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
                ->toArray();
        });

        return response()->json($availableDates);
    }

    /**
     * Obtiene slots disponibles de una fecha específica
     * Sin caché para evitar mostrar slots ocupados
     * Solo muestra slots con categoría "standard"
     */
    public function getAvailableSlots($date)
    {
        // Obtener disponibilidades solo de categoría "standard"
        $availabilities = AppointmentAvailability::where('date', $date)
            ->where('category', 'standard')
            ->select('id', 'start_time', 'end_time', 'duration', 'category')
            ->get();

        if ($availabilities->isEmpty()) {
            return response()->json([]);
        }

        // Obtener TODAS las citas reservadas ACTIVAS de la fecha en una sola consulta
        $bookedSlots = Appointment::where('date', $date)
            ->whereIn('availability_id', $availabilities->pluck('id'))
            ->whereIn('status', ['confirmed', 'pending']) // Solo citas activas, no canceladas ni bloqueadas
            ->select('availability_id', 'start_time')
            ->get()
            ->groupBy('availability_id')
            ->map(fn($items) => $items->pluck('start_time')
                ->map(fn($time) => Carbon::parse($time)->format('H:i'))
                ->toArray()
            );

        $slots = [];

        foreach ($availabilities as $availability) {
            $generatedSlots = $this->generateTimeSlots(
                $availability->start_time,
                $availability->end_time,
                $availability->duration
            );

            $reserved = $bookedSlots->get($availability->id, []);

            foreach ($generatedSlots as $slot) {
                if (!in_array($slot['start'], $reserved)) {
                    $slots[] = [
                        'availability_id' => $availability->id,
                        'start' => $slot['start'],
                        'end' => $slot['end'],
                        'category' => $availability->category,
                    ];
                }
            }
        }

        // Ordenar por hora
        usort($slots, fn($a, $b) => strcmp($a['start'], $b['start']));

        return response()->json($slots);
    }

    /**
     * Reservar una cita
     */
    public function book(Request $request)
    {
        $request->validate([
            'availability_id' => 'required|exists:appointment_availabilities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'cf-turnstile-response' => 'required|string',
        ]);

        // Verificar Cloudflare Turnstile
        $turnstileResponse = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret_key'),
            'response' => $request->input('cf-turnstile-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$turnstileResponse->json('success')) {
            Log::warning('Turnstile verification failed', [
                'ip' => $request->ip(),
                'errors' => $turnstileResponse->json('error-codes'),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'La verificación de seguridad ha fallado. Por favor, inténtalo de nuevo.',
            ], 422);
        }

        // Verificar si el email ya tiene una cita activa (ANTES de la transacción)
        $existingAppointment = Appointment::where('client_email', $request->client_email)
            ->where('date', '>=', now()->toDateString())
            ->whereIn('status', ['confirmed', 'pending'])
            ->first();

        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una cita reservada. Debes cancelarla antes de reservar otra.',
                'existing_appointment' => [
                    'date' => Carbon::parse($existingAppointment->date)->format('d/m/Y'),
                    'time' => Carbon::parse($existingAppointment->start_time)->format('H:i'),
                ]
            ], 409);
        }

        try {
            $appointment = DB::transaction(function () use ($request) {
                // Verificar disponibilidad
                $availability = AppointmentAvailability::findOrFail($request->availability_id);

                // Calcular hora fin
                $startTime = Carbon::parse($request->start_time);
                $endTime = $startTime->copy()->addMinutes($availability->duration);

                // Verificar que no esté ya reservado (doble check con lock) - SOLO citas activas
                $exists = Appointment::where('availability_id', $request->availability_id)
                    ->where('date', $request->date)
                    ->where('start_time', $request->start_time)
                    ->whereIn('status', ['confirmed', 'pending'])
                    ->lockForUpdate()
                    ->exists();

                if ($exists) {
                    abort(409, 'Este horario ya ha sido reservado');
                }

                // Crear reserva
                $appointment = Appointment::create([
                    'availability_id' => $request->availability_id,
                    'date' => $request->date,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'client_name' => $request->client_name,
                    'client_email' => $request->client_email,
                    'client_phone' => $request->client_phone,
                    'status' => 'confirmed',
                ]);

                return $appointment;
            });

            // Enviar email de confirmación (fuera de la transacción)
            Mail::to($appointment->client_email)->send(new AppointmentConfirmation($appointment));

            // Invalidar caché de slots para esta fecha
            $this->clearSlotsCache($request->date);

            return response()->json([
                'success' => true,
                'message' => '¡Cita reservada correctamente!',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar error de unique constraint
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este horario acaba de ser reservado por otra persona.',
                ], 409);
            }
            throw $e;
        }
    }

    /**
     * Genera slots de tiempo según duración
     */
    private function generateTimeSlots($startTime, $endTime, $duration)
    {
        $slots = [];
        $current = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        while ($current->copy()->addMinutes($duration) <= $end) {
            $slots[] = [
                'start' => $current->format('H:i'),
                'end' => $current->copy()->addMinutes($duration)->format('H:i'),
            ];
            $current->addMinutes($duration);
        }

        return $slots;
    }

    /**
     * Limpiar caché de slots para una fecha (todas las horas)
     */
    private function clearSlotsCache($date)
    {
        // Limpiar caché para todas las horas del día
        for ($hour = 0; $hour < 24; $hour++) {
            $hourFormatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
            Cache::forget("slots_standard_{$date}_{$hourFormatted}");
        }
    }

    /**
     * Mostrar página de cancelación
     */
    public function showCancelPage($token)
    {
        $appointment = Appointment::where('cancellation_token', $token)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('date', '>=', now()->toDateString())
            ->first();

        if (!$appointment) {
            return view('calendar.cancel', [
                'appointment' => null,
                'error' => 'La cita no existe, ya fue cancelada o la fecha ya pasó.'
            ]);
        }

        return view('calendar.cancel', [
            'appointment' => $appointment,
            'error' => null
        ]);
    }

    /**
     * Procesar cancelación de cita
     */
    public function cancelAppointment($token)
    {
        $appointment = Appointment::where('cancellation_token', $token)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('date', '>=', now()->toDateString())
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'La cita no existe, ya fue cancelada o la fecha ya pasó.'
            ], 404);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Tu cita ha sido cancelada correctamente.'
        ]);
    }

    /**
     * Verificar si un email tiene cita activa (para el frontend)
     */
    public function checkExistingAppointment(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $appointment = Appointment::where('client_email', $request->email)
            ->where('date', '>=', now()->toDateString())
            ->whereIn('status', ['confirmed', 'pending'])
            ->first();

        if ($appointment) {
            Carbon::setLocale('es');
            return response()->json([
                'has_appointment' => true,
                'appointment' => [
                    'date' => Carbon::parse($appointment->date)->translatedFormat('l, d \d\e F \d\e Y'),
                    'time' => Carbon::parse($appointment->start_time)->format('H:i'),
                    'cancel_url' => $appointment->cancellation_url
                ]
            ]);
        }

        return response()->json([
            'has_appointment' => false
        ]);
    }
    
}
