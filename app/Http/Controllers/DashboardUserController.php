<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardUserController extends Controller
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
        
        // Obtener solo las citas del usuario autenticado (por email)
        $userEmail = Auth::user()->email;
        
        $appointmentsQuery = Appointment::where('client_email', $userEmail)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('availability')
            ->orderBy('date')
            ->orderBy('start_time');
        
        // Si es vista semanal, también incluir citas de la semana (puede cruzar meses)
        if ($view === 'week' && $weekStartDate && $weekEndDate) {
            $appointmentsQuery = Appointment::where('client_email', $userEmail)
                ->whereBetween('date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])
                ->with('availability')
                ->orderBy('date')
                ->orderBy('start_time');
        }
        
        $appointments = $appointmentsQuery->get()
            ->groupBy(function($appointment) {
                return $appointment->date->format('Y-m-d');
            });
        
        // Obtener disponibilidades del mes para mostrar slots disponibles
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
            $allSlots[$date] = $this->generateDaySlots($date, $dayAvailabilities, $appointments[$date] ?? collect(), $userEmail);
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
        
        return view('dashboardUser.index', compact('calendarData'));
    }
    
    /**
     * Generar todos los slots de un día con su estado (adaptado para usuario)
     */
    private function generateDaySlots($date, $availabilities, $appointments, $userEmail)
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
                
                // Determinar si el slot pertenece al usuario actual (solo por email)
                $isUserAppointment = false;
                if ($appointment) {
                    $isUserAppointment = ($appointment->client_email == $userEmail);
                }
                
                $slots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'availability_id' => $availability->id,
                    'availability_title' => $availability->title,
                    'status' => $appointment ? $appointment->status : 'available',
                    'appointment_id' => $appointment ? $appointment->id : null,
                    'client_name' => $isUserAppointment && $appointment ? $appointment->client_name : null,
                    'is_user_appointment' => $isUserAppointment,
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
     * Obtener detalles de una cita del usuario (AJAX)
     */
    public function getAppointment($id)
    {
        $userEmail = Auth::user()->email;
        
        $appointment = Appointment::with('availability')
            ->where('client_email', $userEmail)
            ->findOrFail($id);
        
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
}
