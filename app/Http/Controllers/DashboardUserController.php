<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $userId = Auth::id();
        
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
        
        // Obtener batch_ids de citas asignadas a este usuario
        $assignedBatchIds = DB::table('appointment_availability_user')
            ->where('user_id', $userId)
            ->pluck('batch_id')
            ->toArray();
        
        // Si el usuario no tiene citas asignadas, no mostrar nada
        if (empty($assignedBatchIds)) {
            $availabilities = collect();
        } else {
            // Obtener SOLO las disponibilidades asignadas a este usuario
            $availabilitiesQuery = AppointmentAvailability::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->whereIn('batch_id', $assignedBatchIds)
                ->with('appointments')
                ->orderBy('date')
                ->orderBy('start_time');
            
            // Si es vista semanal, también incluir disponibilidades de la semana (puede cruzar meses)
            if ($view === 'week' && $weekStartDate && $weekEndDate) {
                $availabilitiesQuery = AppointmentAvailability::whereBetween('date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])
                    ->whereIn('batch_id', $assignedBatchIds)
                    ->with('appointments')
                    ->orderBy('date')
                    ->orderBy('start_time');
            }
            
            $availabilities = $availabilitiesQuery->get();
        }
        
        $availabilities = $availabilities->groupBy(function($availability) {
            return $availability->date->format('Y-m-d');
        });
        
        // Generar todos los slots (disponibles y ocupados) por día
        $allSlots = [];
        foreach ($availabilities as $date => $dayAvailabilities) {
            $allSlots[$date] = $this->generateDaySlots($date, $dayAvailabilities, $appointments[$date] ?? collect(), $userEmail, $assignedBatchIds);
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
    private function generateDaySlots($date, $availabilities, $appointments, $userEmail, $assignedBatchIds = [])
    {
        $slots = [];
        
        foreach ($availabilities as $availability) {
            $startTime = Carbon::parse($availability->start_time);
            $endTime = Carbon::parse($availability->end_time);
            $duration = $availability->duration;
            
            // Verificar si esta disponibilidad está asignada al usuario
            $isAssignedToUser = in_array($availability->batch_id, $assignedBatchIds);
            
            while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $startTime->format('H:i');
                $slotEnd = $startTime->copy()->addMinutes($duration)->format('H:i');
                
                // Buscar si hay una cita ACTIVA (confirmed/pending) en este slot primero
                // Si no hay activa, buscar cualquier cita (para saber si fue cancelada)
                $appointment = $appointments->first(function($apt) use ($slotStart, $availability) {
                    $aptTime = substr($apt->start_time, 0, 5);
                    $match = ($aptTime === $slotStart && $apt->availability_id == $availability->id);
                    // Priorizar citas activas
                    return $match && in_array($apt->status, ['confirmed', 'pending']);
                });
                
                // Si no hay cita activa, el slot está disponible (ignorar canceladas)
                // No necesitamos buscar citas canceladas porque el slot estará disponible de todas formas
                
                // Determinar estado del slot
                $slotStatus = 'available';
                $slotAppointmentId = null;
                $slotClientName = null;
                $showAsUserAppointment = false;
                
                if ($appointment) {
                    // Si encontramos una cita activa, establecer sus valores
                    $slotStatus = $appointment->status;
                    $slotAppointmentId = $appointment->id;
                    
                    // Verificar si es del usuario actual
                    if ($appointment->client_email == $userEmail) {
                        $slotClientName = $appointment->client_name;
                        $showAsUserAppointment = true;
                    }
                }
                
                $slots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'availability_id' => $availability->id,
                    'availability_title' => $availability->title,
                    'availability_category' => $availability->category,
                    'batch_id' => $availability->batch_id,
                    'status' => $slotStatus,
                    'appointment_id' => $slotAppointmentId,
                    'client_name' => $slotClientName,
                    'is_user_appointment' => $showAsUserAppointment,
                    'is_assigned_to_user' => $isAssignedToUser, // Flag para saber si es una cita custom asignada
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
    
    /**
     * Reservar un slot disponible desde el dashboard
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'availability_id' => 'required|exists:appointment_availabilities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
        ]);

        $user = Auth::user();
        $userEmail = $user->email;

        // Verificar que no tenga una cita activa
        $existingAppointment = Appointment::where('client_email', $userEmail)
            ->where('date', '>=', now()->toDateString())
            ->whereIn('status', ['confirmed', 'pending'])
            ->first();

        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una cita reservada. Debes cancelarla antes de reservar otra.',
                'has_existing' => true,
                'existing_appointment' => [
                    'id' => $existingAppointment->id,
                    'date' => Carbon::parse($existingAppointment->date)->format('d/m/Y'),
                    'time' => Carbon::parse($existingAppointment->start_time)->format('H:i'),
                ]
            ], 409);
        }

        try {
            $appointment = DB::transaction(function () use ($request, $userEmail, $user) {
                // Verificar disponibilidad
                $availability = AppointmentAvailability::findOrFail($request->availability_id);

                // Calcular hora fin
                $startTime = Carbon::parse($request->start_time);
                $endTime = $startTime->copy()->addMinutes($availability->duration);

                // Verificar que no esté ya reservado
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
                    'client_name' => $user->name,
                    'client_email' => $userEmail,
                    'client_phone' => $user->phone ?? '',
                    'status' => 'confirmed',
                ]);

                return $appointment;
            });

            return response()->json([
                'success' => true,
                'message' => '¡Cita reservada correctamente!',
                'appointment' => [
                    'id' => $appointment->id,
                    'date' => $appointment->date->format('d/m/Y'),
                    'time' => substr($appointment->start_time, 0, 5),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reservar la cita: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Cancelar una cita reservada
     */
    public function cancelAppointment($id)
    {
        $userEmail = Auth::user()->email;

        $appointment = Appointment::where('client_email', $userEmail)
            ->findOrFail($id);

        if (!in_array($appointment->status, ['confirmed', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar una cita que ya ha sido cancelada o completada.',
            ], 422);
        }

        try {
            // Eliminar la cita en lugar de cambiar su estado para evitar conflictos con el constraint único
            $appointment->delete();

            return response()->json([
                'success' => true,
                'message' => '¡Cita cancelada correctamente!',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cancelar cita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la cita. Por favor, inténtalo de nuevo.',
            ], 500);
        }
    }
}
