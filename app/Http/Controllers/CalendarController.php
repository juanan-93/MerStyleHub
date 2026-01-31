<?php

namespace App\Http\Controllers;

use App\Models\AppointmentAvailability;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
     * Optimizado con una sola consulta y caché
     * Solo muestra slots con categoría "standard"
     */
    public function getAvailableSlots($date)
    {
        $cacheKey = "slots_standard_{$date}_" . now()->format('H');
        
        $slots = Cache::remember($cacheKey, 60, function () use ($date) {
            // Obtener disponibilidades solo de categoría "standard"
            $availabilities = AppointmentAvailability::where('date', $date)
                ->where('category', 'standard')
                ->select('id', 'start_time', 'end_time', 'duration', 'category')
                ->get();

            if ($availabilities->isEmpty()) {
                return [];
            }

            // Obtener TODAS las citas reservadas de la fecha en una sola consulta
            $bookedSlots = Appointment::where('date', $date)
                ->whereIn('availability_id', $availabilities->pluck('id'))
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

            return $slots;
        });

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

                // Verificar que no esté ya reservado (doble check con lock)
                $exists = Appointment::where('availability_id', $request->availability_id)
                    ->where('date', $request->date)
                    ->where('start_time', $request->start_time)
                    ->lockForUpdate()
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

                // Invalidar caché de slots para esta fecha
                Cache::forget("slots_standard_{$request->date}_" . now()->format('H'));
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
