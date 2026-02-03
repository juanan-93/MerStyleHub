<?php

namespace App\Http\Controllers;
use App\Models\AppointmentAvailability;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AppointmentAvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = AppointmentAvailability::select('batch_id', 'title', 'category', 'duration', 'selection_type')
            ->selectRaw('MIN(date) as start_date, MAX(date) as end_date, COUNT(*) as total_days')
            ->groupBy('batch_id', 'title', 'category', 'duration', 'selection_type')
            ->get();

        // Cargar usuarios asignados para cada batch de categoría custom
        $assignedUsers = [];
        foreach ($availabilities as $availability) {
            if ($availability->category === 'custom') {
                $users = DB::table('appointment_availability_user')
                    ->join('users', 'appointment_availability_user.user_id', '=', 'users.id')
                    ->where('appointment_availability_user.batch_id', $availability->batch_id)
                    ->select('users.name', 'users.email')
                    ->get();
                $assignedUsers[$availability->batch_id] = $users;
            }
        }

        return view('admin_appointments.index', compact('availabilities', 'assignedUsers'));
    } 
    
    public function create()
    {
        // Obtener solo usuarios con rol customer
        $users = User::role('customer')->orderBy('name')->get();
        return view('admin_appointments.create', compact('users'));
    } 

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'category' => 'required',
            'selection_type' => 'required|in:range,custom,weekdays',
            'schedule_data' => 'required|json',
        ]);

        $batchId = (string) Str::uuid();
        $selectionType = $request->selection_type;
        $scheduleData = json_decode($request->schedule_data, true);

        // Validar que schedule_data no esté vacío
        if (empty($scheduleData) || !is_array($scheduleData)) {
            return back()->withErrors(['schedule_data' => 'Debe configurar al menos un día con horarios antes de guardar.'])->withInput();
        }

        // Validar que no haya conflictos de horario
        $conflicts = [];
        foreach ($scheduleData as $daySchedule) {
            $date = $daySchedule['date'];
            $startTime = $daySchedule['start_time'];
            $endTime = $daySchedule['end_time'];

            if (!empty($date) && !empty($startTime) && !empty($endTime)) {
                if (AppointmentAvailability::hasTimeConflict($date, $startTime, $endTime)) {
                    $conflicts[] = "Fecha {$date}: {$startTime} - {$endTime}";
                }
            }
        }

        if (!empty($conflicts)) {
            return back()
                ->withErrors(['schedule_data' => 'Existen conflictos de horario en: ' . implode(', ', $conflicts)])
                ->withInput();
        }

        // Procesar cada día con su horario (general o personalizado)
        foreach ($scheduleData as $daySchedule) {
            $date = $daySchedule['date'];
            $startTime = $daySchedule['start_time'];
            $endTime = $daySchedule['end_time'];

            if (!empty($date) && !empty($startTime) && !empty($endTime)) {
                AppointmentAvailability::create([
                    'batch_id' => $batchId,
                    'title' => $request->title,
                    'date' => $date,
                    'duration' => $request->duration,
                    'category' => $request->category,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'selection_type' => $selectionType,
                ]);
            }
        }

        // Si es categoría custom, asignar usuarios seleccionados
        if ($request->category === 'custom' && $request->has('assigned_users') && !empty($request->assigned_users)) {
            $userIds = $request->assigned_users;
            foreach ($userIds as $userId) {
                DB::table('appointment_availability_user')->insert([
                    'batch_id' => $batchId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Crear notificación para el usuario
                Notification::appointmentAvailable($userId, $request->title);
            }
        }

        return redirect()->route('admin_appointments.index')->with('success', 'Disponibilidad configurada correctamente.');
    }

    public function edit($batch_id)
    {
        $availabilities = AppointmentAvailability::where('batch_id', $batch_id)->get();
        
        if ($availabilities->isEmpty()) {
            return redirect()->route('admin_appointments.index')->with('error', 'No se encontró la disponibilidad.');
        }

        $availability = $availabilities->first();
        $selectionType = $availability->selection_type;
        
        // Preparar datos de horarios por día - Agrupados por fecha para manejar múltiples franjas
        $scheduleDataGrouped = [];
        foreach ($availabilities as $item) {
            $date = $item->date->format('Y-m-d');
            if (!isset($scheduleDataGrouped[$date])) {
                $scheduleDataGrouped[$date] = [];
            }
            $scheduleDataGrouped[$date][] = [
                'start_time' => substr($item->start_time, 0, 5),
                'end_time' => substr($item->end_time, 0, 5),
            ];
        }

        // Convertir a formato plano para JavaScript
        $scheduleData = [];
        foreach ($scheduleDataGrouped as $date => $slots) {
            foreach ($slots as $slot) {
                $scheduleData[] = [
                    'date' => $date,
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                ];
            }
        }

        $datesList = array_keys($scheduleDataGrouped);

        // Obtener todos los usuarios con rol customer
        $users = User::role('customer')->orderBy('name')->get();
        
        // Obtener usuarios asignados a este batch
        $assignedUserIds = DB::table('appointment_availability_user')
            ->where('batch_id', $batch_id)
            ->pluck('user_id')
            ->toArray();

        return view('admin_appointments.edit', compact(
            'availability', 
            'batch_id', 
            'selectionType', 
            'scheduleData',
            'datesList',
            'users',
            'assignedUserIds'
        ));
    }

    public function updateBatch(Request $request, $batch_id)
    {
        if (empty($batch_id)) {
            return redirect()->route('admin_appointments.index')->with('error', 'ID de lote no válido.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'category' => 'required',
            'selection_type' => 'required|in:range,custom,weekdays',
            'schedule_data' => 'required|json',
        ]);

        $selectionType = $request->selection_type;
        $scheduleData = json_decode($request->schedule_data, true);

        // Validar que schedule_data no esté vacío
        if (empty($scheduleData) || !is_array($scheduleData)) {
            return back()->withErrors(['schedule_data' => 'Debe configurar al menos un día con horarios antes de guardar.'])->withInput();
        }

        // Validar que no haya conflictos de horario (excluyendo el batch actual)
        $conflicts = [];
        foreach ($scheduleData as $daySchedule) {
            $date = $daySchedule['date'];
            $startTime = $daySchedule['start_time'];
            $endTime = $daySchedule['end_time'];

            if (!empty($date) && !empty($startTime) && !empty($endTime)) {
                if (AppointmentAvailability::hasTimeConflict($date, $startTime, $endTime, $batch_id)) {
                    $conflicts[] = "Fecha {$date}: {$startTime} - {$endTime}";
                }
            }
        }

        if (!empty($conflicts)) {
            return back()
                ->withErrors(['schedule_data' => 'Existen conflictos de horario en: ' . implode(', ', $conflicts)])
                ->withInput();
        }

        // Eliminamos el lote anterior para recrearlo con los nuevos datos/fechas
        AppointmentAvailability::where('batch_id', $batch_id)->delete();

        // Procesar cada día con su horario
        foreach ($scheduleData as $daySchedule) {
            $date = $daySchedule['date'];
            $startTime = $daySchedule['start_time'];
            $endTime = $daySchedule['end_time'];

            if (!empty($date) && !empty($startTime) && !empty($endTime)) {
                AppointmentAvailability::create([
                    'batch_id' => $batch_id,
                    'title' => $request->title,
                    'date' => $date,
                    'duration' => $request->duration,
                    'category' => $request->category,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'selection_type' => $selectionType,
                ]);
            }
        }

        // Actualizar asignaciones de usuarios si es categoría custom
        if ($request->category === 'custom') {
            // Obtener usuarios asignados anteriormente
            $previousUserIds = DB::table('appointment_availability_user')
                ->where('batch_id', $batch_id)
                ->pluck('user_id')
                ->toArray();
            
            // Eliminar asignaciones anteriores
            DB::table('appointment_availability_user')->where('batch_id', $batch_id)->delete();
            
            // Insertar nuevas asignaciones
            if ($request->has('assigned_users') && !empty($request->assigned_users)) {
                $userIds = $request->assigned_users;
                foreach ($userIds as $userId) {
                    DB::table('appointment_availability_user')->insert([
                        'batch_id' => $batch_id,
                        'user_id' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    // Notificar solo a usuarios nuevos (no estaban asignados antes)
                    if (!in_array($userId, $previousUserIds)) {
                        Notification::appointmentAvailable($userId, $request->title);
                    }
                }
            }
        }

        return redirect()->route('admin_appointments.index')->with('success', 'Disponibilidad actualizada correctamente.');
    }

    public function destroyBatch($batch_id)
    {
        AppointmentAvailability::where('batch_id', $batch_id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Valida en tiempo real si hay conflictos de horario (endpoint AJAX)
     */
    public function checkTimeConflicts(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $date = $request->date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $batchId = $request->batch_id ?? null;

        $conflicts = AppointmentAvailability::getConflicts($date, $startTime, $endTime, $batchId);

        if ($conflicts->isNotEmpty()) {
            return response()->json([
                'has_conflict' => true,
                'conflicts' => $conflicts->map(function($c) {
                    return [
                        'title' => $c->title,
                        'start_time' => substr($c->start_time, 0, 5),
                        'end_time' => substr($c->end_time, 0, 5),
                        'duration' => $c->duration,
                    ];
                })
            ]);
        }

        return response()->json(['has_conflict' => false]);
    }
}
