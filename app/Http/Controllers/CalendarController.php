<?php

namespace App\Http\Controllers;

use App\Models\AppointmentAvailability;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Obtiene fechas con disponibilidad (para pintar dots en calendario)
     */
    public function getAvailableDates()
    {
        $availableDates = AppointmentAvailability::where('date', '>=', now()->toDateString())
            ->distinct()
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        return response()->json($availableDates);
    }

    /**
     * Obtiene slots disponibles de una fecha específica
     */
    public function getAvailableSlots($date)
    {
        $availabilities = AppointmentAvailability::where('date', $date)->get();

        if ($availabilities->isEmpty()) {
            return response()->json([]);
        }

        $slots = [];

        foreach ($availabilities as $availability) {
            // Generar slots según duración
            $generatedSlots = $this->generateTimeSlots(
                $availability->start_time,
                $availability->end_time,
                $availability->duration
            );

            // Obtener slots ya reservados
            $bookedSlots = Appointment::where('availability_id', $availability->id)
                ->where('date', $date)
                ->pluck('start_time')
                ->map(fn($time) => Carbon::parse($time)->format('H:i'))
                ->toArray();

            // Filtrar slots disponibles
            foreach ($generatedSlots as $slot) {
                if (!in_array($slot['start'], $bookedSlots)) {
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
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Verificar disponibilidad
                $availability = AppointmentAvailability::findOrFail($request->availability_id);

                // Calcular hora fin
                $startTime = Carbon::parse($request->start_time);
                $endTime = $startTime->copy()->addMinutes($availability->duration);

                // Verificar que no esté ya reservado (doble check por si acaso)
                $exists = Appointment::where('availability_id', $request->availability_id)
                    ->where('date', $request->date)
                    ->where('start_time', $request->start_time)
                    ->exists();

                if ($exists) {
                    abort(409, 'Este horario ya ha sido reservado');
                }

                // Crear reserva
                Appointment::create([
                    'availability_id' => $request->availability_id,
                    'date' => $request->date,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'client_name' => $request->client_name,
                    'client_email' => $request->client_email,
                    'client_phone' => $request->client_phone,
                    'status' => 'confirmed',
                ]);
            });

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
    
}
