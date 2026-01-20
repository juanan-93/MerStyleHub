<?php

namespace App\Http\Controllers;
use App\Models\AppointmentAvailability;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

class AppointmentAvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = AppointmentAvailability::select('batch_id', 'title', 'category', 'duration', 'selection_type')
            ->selectRaw('MIN(date) as start_date, MAX(date) as end_date, COUNT(*) as total_days')
            ->groupBy('batch_id', 'title', 'category', 'duration', 'selection_type')
            ->get();

        return view('admin_appointments.index', compact('availabilities'));
    } 
    
    public function create()
    {
        return view('admin_appointments.create');
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
        
        // Preparar datos de horarios por día
        $scheduleData = $availabilities->map(function ($item) {
            return [
                'date' => $item->date->format('Y-m-d'),
                'start_time' => substr($item->start_time, 0, 5),
                'end_time' => substr($item->end_time, 0, 5),
            ];
        })->toArray();

        $datesList = $availabilities->pluck('date')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        return view('admin_appointments.edit', compact(
            'availability', 
            'batch_id', 
            'selectionType', 
            'scheduleData',
            'datesList'
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

        // Eliminamos el lote anterior para recrearlo con los nuevos datos/fechas
        AppointmentAvailability::where('batch_id', $batch_id)->delete();

        $selectionType = $request->selection_type;
        $scheduleData = json_decode($request->schedule_data, true);

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

        return redirect()->route('admin_appointments.index')->with('success', 'Disponibilidad actualizada correctamente.');
    }

    public function destroyBatch($batch_id)
    {
        AppointmentAvailability::where('batch_id', $batch_id)->delete();
        return response()->json(['success' => true]);
    }
}
