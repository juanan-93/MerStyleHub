<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el mes y año actual o de la petición
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $view = $request->get('view', 'month');
        $weekStart = $request->get('week_start');
        
        // Crear fecha base para el mes
        $currentDate = Carbon::createFromDate($year, $month, 1);
        
        // Variables para la vista semanal
        $weekStartDate = null;
        $weekEndDate = null;
        
        if ($view === 'week' && $weekStart) {
            $weekStartDate = Carbon::parse($weekStart)->startOfWeek();
            $weekEndDate = $weekStartDate->copy()->endOfWeek();
            // Ajustar mes y año basándose en la semana
            $month = $weekStartDate->month;
            $year = $weekStartDate->year;
            $currentDate = $weekStartDate->copy()->startOfMonth();
        }
        
        // Obtener citas del mes (reservadas y bloqueadas)
        $appointmentsQuery = Appointment::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('availability')
            ->orderBy('date')
            ->orderBy('start_time');
        
        // Si es vista semanal, también incluir citas de la semana (puede cruzar meses)
        if ($view === 'week' && $weekStartDate && $weekEndDate) {
            $appointmentsQuery = Appointment::whereBetween('date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])
                ->with('availability')
                ->orderBy('date')
                ->orderBy('start_time');
        }
        
        $appointments = $appointmentsQuery->get()
            ->groupBy(function($appointment) {
                return $appointment->date->format('Y-m-d');
            });
        
        // Obtener disponibilidades del mes para generar slots disponibles
        $availabilitiesQuery = AppointmentAvailability::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('appointments')
            ->orderBy('date')
            ->orderBy('start_time');
        
        // Si es vista semanal, también incluir disponibilidades de la semana (puede cruzar meses)
        if ($view === 'week' && $weekStartDate && $weekEndDate) {
            $availabilitiesQuery = AppointmentAvailability::whereBetween('date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])
                ->with('appointments')
                ->orderBy('date')
                ->orderBy('start_time');
        }
        
        $availabilities = $availabilitiesQuery->get()
            ->groupBy(function($availability) {
                return $availability->date->format('Y-m-d');
            });
        
        // Generar todos los slots (disponibles y ocupados) por día
        $allSlots = [];
        foreach ($availabilities as $date => $dayAvailabilities) {
            $allSlots[$date] = $this->generateDaySlots($date, $dayAvailabilities, $appointments[$date] ?? collect());
        }
        
        // Datos para la navegación del calendario
        $calendarData = [
            'currentMonth' => $currentDate,
            'monthName' => $currentDate->locale('es')->isoFormat('MMMM YYYY'),
            'daysInMonth' => $currentDate->daysInMonth,
            'firstDayOfWeek' => $currentDate->copy()->startOfMonth()->dayOfWeekIso, // 1=Lunes, 7=Domingo
            'appointments' => $appointments,
            'allSlots' => $allSlots,
            'today' => now()->format('Y-m-d'),
            'view' => $view,
            'weekStart' => $weekStartDate ? $weekStartDate->format('Y-m-d') : now()->startOfWeek()->format('Y-m-d'),
            'weekEnd' => $weekEndDate ? $weekEndDate->format('Y-m-d') : now()->endOfWeek()->format('Y-m-d'),
        ];
        
        return view('dashboardAdmin.index', compact('calendarData'));
    }
    
    /**
     * Generar todos los slots de un día con su estado
     */
    private function generateDaySlots($date, $availabilities, $appointments)
    {
        $slots = [];
        
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);
            $duration = $availability->duration;
            
            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $startTime->format('H:i');
                $slotEnd = $startTime->copy()->addMinutes($duration)->format('H:i');
                
                // Buscar si hay una cita en este slot
                $appointment = $appointments->first(function($apt) use ($slotStart) {
                    return substr($apt->start_time, 0, 5) === $slotStart;
                });
                
                $slots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'availability_id' => $availability->id,
                    'availability_title' => $availability->title,
                    'status' => $appointment ? $appointment->status : 'available',
                    'appointment_id' => $appointment ? $appointment->id : null,
                    'client_name' => $appointment ? $appointment->client_name : null,
                ];
                
                $startTime->addMinutes($duration);
            }
        }
        
        // Ordenar por hora
        usort($slots, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });
        
        return $slots;
    }

    /**
     * Obtener detalles de una cita (AJAX)
     */
    public function getAppointment($id)
    {
        $appointment = Appointment::with('availability')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'client_name' => $appointment->client_name,
                'client_email' => $appointment->client_email,
                'client_phone' => $appointment->client_phone,
                'date' => $appointment->date->format('Y-m-d'),
                'date_formatted' => $appointment->date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                'start_time' => substr($appointment->start_time, 0, 5),
                'end_time' => substr($appointment->end_time, 0, 5),
                'status' => $appointment->status,
                'notes' => $appointment->notes,
                'availability' => $appointment->availability ? [
                    'title' => $appointment->availability->title,
                    'category' => $appointment->availability->category,
                    'duration' => $appointment->availability->duration,
                ] : null,
            ]
        ]);
    }

    /**
     * Actualizar estado de una cita
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        $statusLabels = [
            'pending' => 'pendiente',
            'confirmed' => 'confirmada',
            'cancelled' => 'cancelada'
        ];

        return response()->json([
            'success' => true,
            'message' => "Cita marcada como {$statusLabels[$request->status]}",
            'appointment' => $appointment
        ]);
    }

    /**
     * Mover/Reubicar una cita a otra fecha u horario
     */
    public function moveAppointment(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $appointment = Appointment::findOrFail($id);
        
        // Verificar que no hay conflicto en el nuevo horario
        $conflict = Appointment::where('id', '!=', $id)
            ->where('date', $request->date)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una cita en ese horario'
            ], 400);
        }

        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Cita reubicada correctamente',
            'appointment' => $appointment
        ]);
    }

    /**
     * Eliminar una cita
     */
    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada correctamente'
        ]);
    }

    /**
     * Obtener citas para un mes específico (AJAX para navegación)
     */
    public function getMonthAppointments(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $appointments = Appointment::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('availability')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'client_name' => $appointment->client_name,
                    'date' => $appointment->date->format('Y-m-d'),
                    'start_time' => substr($appointment->start_time, 0, 5),
                    'end_time' => substr($appointment->end_time, 0, 5),
                    'status' => $appointment->status,
                    'title' => $appointment->availability->title ?? 'Cita',
                ];
            });

        // Agrupar por fecha
        $grouped = $appointments->groupBy('date');

        return response()->json([
            'success' => true,
            'appointments' => $grouped,
            'month' => $month,
            'year' => $year
        ]);
    }

    /**
     * Obtener fechas que tienen disponibilidades configuradas
     */
    public function getAvailableDates()
    {
        // Obtener todas las fechas con disponibilidades (pasadas y futuras)
        $dates = AppointmentAvailability::orderBy('date')
            ->select('date', 'title')
            ->distinct('date')
            ->get()
            ->groupBy('date')
            ->map(function ($group) {
                $isPast = $group->first()->date->lt(now()->startOfDay());
                return [
                    'date' => $group->first()->date->format('Y-m-d'),
                    'date_formatted' => $group->first()->date->format('d/m/Y'),
                    'day_name' => ucfirst($group->first()->date->translatedFormat('l d F')),
                    'titles' => $group->pluck('title')->unique()->implode(', '),
                    'is_past' => $isPast
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'dates' => $dates
        ]);
    }

    /**
     * Bloquear un slot/horario (para emergencias)
     */
    public function blockSlot(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'nullable|string|max:255'
        ]);

        // Crear una cita "bloqueada" sin cliente
        $appointment = Appointment::create([
            'client_name' => 'BLOQUEADO',
            'client_email' => 'blocked@system.local',
            'client_phone' => '',
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'blocked',
            'notes' => $request->reason ?? 'Horario bloqueado por emergencia',
            'availability_id' => $request->availability_id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Horario bloqueado correctamente',
            'appointment' => $appointment
        ]);
    }

    /**
     * Desbloquear un slot
     */
    public function unblockSlot($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('status', 'blocked')
            ->firstOrFail();
        
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Horario desbloqueado correctamente'
        ]);
    }

    /**
     * Obtener slots disponibles para una fecha (para mover citas)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->get('date');
        
        // Obtener disponibilidades para esa fecha
        $availabilities = AppointmentAvailability::whereDate('date', $date)->get();
        
        // Obtener citas existentes para esa fecha (excluyendo canceladas)
        // Las citas bloqueadas también ocupan el slot
        $existingAppointments = Appointment::whereDate('date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get();

        $slots = [];
        
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);
            $duration = $availability->duration;

            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $startTime->format('H:i');
                $slotEnd = $startTime->copy()->addMinutes($duration)->format('H:i');
                
                // Verificar si el slot está ocupado (por cita normal o bloqueada)
                $isBooked = $existingAppointments->contains(function($apt) use ($slotStart) {
                    return substr($apt->start_time, 0, 5) === $slotStart;
                });

                if (!$isBooked) {
                    $slots[] = [
                        'start_time' => $slotStart,
                        'end_time' => $slotEnd,
                        'availability_id' => $availability->id,
                    ];
                }

                $startTime->addMinutes($duration);
            }
        }

        return response()->json([
            'success' => true,
            'slots' => $slots,
            'date' => $date
        ]);
    }
}
