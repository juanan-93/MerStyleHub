<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use App\Models\User;
use App\Models\Product;
use App\Models\CustomerProfile;
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
        
        // === DATOS PARA EL DASHBOARD ===
        
        // Total de clientes (usuarios con rol customer)
        $totalClientes = User::role('customer')->count();
        
        // Total de servicios (productos)
        $totalServicios = Product::count();
        
        // Calcular ingresos totales según tipo de servicio (presencial/online)
        $totalIngresos = CustomerProfile::whereNotNull('product_id')
            ->whereNotNull('percentage_paid')
            ->with('product')
            ->get()
            ->sum(function ($profile) {
                if ($profile->product && $profile->percentage_paid > 0) {
                    $price = $profile->service_type === 'online' 
                        ? $profile->product->price_online 
                        : $profile->product->price_presencial;
                    return ($profile->percentage_paid / 100) * $price;
                }
                return 0;
            });
        
        // Datos para el gráfico: Ingresos de los últimos 12 meses
        $chartData = $this->getMonthlyRevenueData();
        
        // Estado de pagos de usuarios - Mostrar todos los clientes (con y sin servicio)
        // Primero obtenemos todos los usuarios con rol 'customer'
        $customers = User::role('customer')
            ->with(['customerProfile.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Transformar a un formato similar al anterior para mantener compatibilidad con la vista
        $userPaymentStatus = $customers->map(function ($user) {
            // Si el usuario no tiene perfil, crear uno temporal para la vista
            if (!$user->customerProfile) {
                $tempProfile = new CustomerProfile();
                $tempProfile->user_id = $user->id;
                $tempProfile->created_at = $user->created_at;
                $tempProfile->user = $user;
                return $tempProfile;
            }
            return $user->customerProfile;
        });
        
        // Datos del dashboard
        $dashboardData = [
            'totalClientes' => $totalClientes,
            'totalServicios' => $totalServicios,
            'totalIngresos' => $totalIngresos,
            'chartLabels' => $chartData['labels'],
            'chartValues' => $chartData['values'],
            'userPaymentStatus' => $userPaymentStatus,
        ];
        
        return view('dashboardAdmin.index', compact('calendarData', 'dashboardData'));
    }
    
    /**
     * Obtener datos de ingresos mensuales para el gráfico
     */
    private function getMonthlyRevenueData(): array
    {
        $labels = [];
        $values = [];
        
        // Últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->locale('es')->isoFormat('MMM');
            
            // Calcular ingresos del mes según tipo de servicio
            // Usa payment_date si existe, si no usa created_at como referencia
            $monthlyRevenue = CustomerProfile::whereNotNull('product_id')
                ->whereNotNull('percentage_paid')
                ->where(function($query) use ($date) {
                    $query->where(function($q) use ($date) {
                        // Si tiene payment_date, usar esa fecha
                        $q->whereNotNull('payment_date')
                          ->whereYear('payment_date', $date->year)
                          ->whereMonth('payment_date', $date->month);
                    })->orWhere(function($q) use ($date) {
                        // Si no tiene payment_date pero sí percentage_paid > 0, usar created_at
                        $q->whereNull('payment_date')
                          ->where('percentage_paid', '>', 0)
                          ->whereYear('created_at', $date->year)
                          ->whereMonth('created_at', $date->month);
                    });
                })
                ->with('product')
                ->get()
                ->sum(function ($profile) {
                    if ($profile->product && $profile->percentage_paid > 0) {
                        $price = $profile->service_type === 'online' 
                            ? ($profile->product->price_online ?? 0)
                            : ($profile->product->price_presencial ?? 0);
                        return ($profile->percentage_paid / 100) * $price;
                    }
                    return 0;
                });
            
            $values[] = round($monthlyRevenue, 2);
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
        ];
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
                
                // Buscar si hay una cita ACTIVA en este slot para esta disponibilidad específica
                $appointment = $appointments->first(function($apt) use ($slotStart, $availability) {
                    $aptTime = substr($apt->start_time, 0, 5);
                    // Debe coincidir hora Y availability_id, y estar activa (confirmed/pending)
                    return $aptTime === $slotStart 
                        && $apt->availability_id == $availability->id
                        && in_array($apt->status, ['confirmed', 'pending']);
                });
                
                // Si no hay cita activa, buscar si hay una cita bloqueada
                if (!$appointment) {
                    $appointment = $appointments->first(function($apt) use ($slotStart, $availability) {
                        $aptTime = substr($apt->start_time, 0, 5);
                        return $aptTime === $slotStart 
                            && $apt->availability_id == $availability->id
                            && $apt->status === 'blocked';
                    });
                }
                
                $slots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'availability_id' => $availability->id,
                    'availability_title' => $availability->title,
                    'availability_category' => $availability->category ?? null,
                    'status' => $appointment ? $appointment->status : 'available',
                    'appointment_id' => $appointment ? $appointment->id : null,
                    'client_name' => $appointment ? $appointment->client_name : null,
                    'client_email' => $appointment ? $appointment->client_email : null,
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

        $appointment = Appointment::with('availability')->findOrFail($id);
        $previousStatus = $appointment->status;
        
        $statusLabels = [
            'pending' => 'pendiente',
            'confirmed' => 'confirmada',
            'cancelled' => 'cancelada'
        ];
        
        // Si el admin cancela la cita, notificar al usuario y ELIMINARLA
        // (eliminamos en lugar de cambiar status para liberar el slot)
        if ($request->status === 'cancelled' && $previousStatus !== 'cancelled') {
            $appointmentData = [
                'date' => $appointment->date->format('d/m/Y'),
                'time' => substr($appointment->start_time, 0, 5),
                'id' => $appointment->id,
                'title' => $appointment->availability?->title ?? 'Cita',
            ];
            
            if ($appointment->client_email) {
                $user = \App\Models\User::where('email', $appointment->client_email)->first();
                if ($user) {
                    \App\Models\Notification::appointmentCancelled($user->id, $appointmentData);
                }
            }
            
            // Eliminar la cita en lugar de cambiar su status
            $appointment->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Cita cancelada y eliminada correctamente",
                'deleted' => true
            ]);
        }
        
        // Para otros cambios de status (pending, confirmed), actualizar normalmente
        $appointment->status = $request->status;
        $appointment->save();

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
        $appointment = Appointment::with('availability')->findOrFail($id);
        
        // Guardar datos antes de eliminar para la notificación
        $appointmentData = [
            'date' => $appointment->date->format('d/m/Y'),
            'time' => substr($appointment->start_time, 0, 5),
            'id' => $appointment->id,
            'title' => $appointment->availability?->title ?? 'Cita',
        ];
        
        // Obtener email del cliente para buscar el usuario
        $clientEmail = $appointment->client_email;
        
        // Eliminar la cita
        $appointment->delete();
        
        // Notificar al usuario que su cita fue cancelada por el admin
        if ($clientEmail) {
            $user = \App\Models\User::where('email', $clientEmail)->first();
            if ($user) {
                \App\Models\Notification::appointmentCancelled($user->id, $appointmentData);
            }
        }

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
