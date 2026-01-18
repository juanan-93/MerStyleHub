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
        $availabilities = AppointmentAvailability::select('batch_id', 'category', 'start_time', 'end_time', 'duration', 'selection_type')
            ->selectRaw('MIN(date) as start_date, MAX(date) as end_date, COUNT(*) as total_days')
            ->groupBy('batch_id', 'category', 'start_time', 'end_time', 'duration', 'selection_type')
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
            'dates' => 'required',
            'duration' => 'required|integer',
            'category' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'selection_type' => 'required|in:range,custom,weekdays',
        ]);

        $datesStr = str_replace(' a ', ' to ', $request->dates);
        $batchId = (string) Str::uuid();
        $selectionType = $request->selection_type ?? 'range'; // Usar el valor del formulario

        // Procesar fechas según el tipo de selección
        if ($selectionType === 'custom' || $selectionType === 'weekdays') {
            // Limpiar prefijo CUSTOM: si existe y separar por comas
            $cleanDates = str_replace('CUSTOM:', '', $datesStr);
            $customDates = explode(',', $cleanDates);
            
            // Crear un registro por cada fecha seleccionada
            foreach ($customDates as $date) {
                $date = trim($date); // Limpiar espacios
                if (!empty($date)) {
                    AppointmentAvailability::create([
                        'batch_id' => $batchId,
                        'date' => $date,
                        'duration' => $request->duration,
                        'category' => $request->category,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'selection_type' => $selectionType,
                    ]);
                }
            }
        } 
        // Procesar fechas en formato RANGO:
        elseif (str_contains($datesStr, ' to ')) {
            $parts = explode(' to ', $datesStr);
            $period = CarbonPeriod::create($parts[0], $parts[1]);
            
            foreach ($period as $date) {
                AppointmentAvailability::create([
                    'batch_id' => $batchId,
                    'date' => $date->format('Y-m-d'),
                    'duration' => $request->duration,
                    'category' => $request->category,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'selection_type' => $selectionType,
                ]);
            }
        } 
        // Fallback: una única fecha
        else {
            AppointmentAvailability::create([
                'batch_id' => $batchId,
                'date' => $datesStr,
                'duration' => $request->duration,
                'category' => $request->category,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'selection_type' => $selectionType,
            ]);
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
        
        $minDate = $availabilities->min('date');
        $maxDate = $availabilities->max('date');
        $datesList = $availabilities->pluck('date')->toArray();

        // Determinar el formato de fechas según el tipo de selección
        if ($selectionType === 'custom' || $selectionType === 'weekdays') {
            // Para custom y weekdays, pasar array JSON de fechas y también el formato string (sin prefijo para evitar errores en flatpickr)
            $dates = implode(',', $datesList);
            $datesArray = json_encode($datesList);
        } else {
            // Para range y otros, mostrar el rango
            $dates = ($minDate == $maxDate) ? $minDate : "$minDate to $maxDate";
            $datesArray = json_encode([$minDate, $maxDate]);
        }

        return view('admin_appointments.edit', compact('availability', 'dates', 'datesArray', 'batch_id', 'selectionType'));
    }

    public function updateBatch(Request $request, $batch_id)
    {
        if (empty($batch_id)) {
            return redirect()->route('admin_appointments.index')->with('error', 'ID de lote no válido.');
        }

        $request->validate([
            'dates' => 'required',
            'duration' => 'required|integer',
            'category' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'selection_type' => 'required|in:range,custom,weekdays',
        ]);

        // Eliminamos el lote anterior para recrearlo con los nuevos datos/fechas
        AppointmentAvailability::where('batch_id', $batch_id)->delete();

        $datesStr = str_replace(' a ', ' to ', $request->dates);
        $selectionType = $request->selection_type ?? 'range'; // Usar el valor del formulario

        // Procesar fechas según el tipo de selección
        if ($selectionType === 'custom' || $selectionType === 'weekdays') {
            // Limpiar prefijo CUSTOM: si existe y separar por comas
            $cleanDates = str_replace('CUSTOM:', '', $datesStr);
            $customDates = explode(',', $cleanDates);
            
            // Crear un registro por cada fecha seleccionada
            foreach ($customDates as $date) {
                $date = trim($date); // Limpiar espacios
                if (!empty($date)) {
                    AppointmentAvailability::create([
                        'batch_id' => $batch_id,
                        'date' => $date,
                        'duration' => $request->duration,
                        'category' => $request->category,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'selection_type' => $selectionType,
                    ]);
                }
            }
        }
        // Procesar fechas en formato RANGO:
        elseif (str_contains($datesStr, ' to ')) {
            $parts = explode(' to ', $datesStr);
            $period = CarbonPeriod::create($parts[0], $parts[1]);
            
            foreach ($period as $date) {
                AppointmentAvailability::create([
                    'batch_id' => $batch_id,
                    'date' => $date->format('Y-m-d'),
                    'duration' => $request->duration,
                    'category' => $request->category,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'selection_type' => $selectionType,
                ]);
            }
        }
        // Fallback: una única fecha
        else {
            AppointmentAvailability::create([
                'batch_id' => $batch_id,
                'date' => $datesStr,
                'duration' => $request->duration,
                'category' => $request->category,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'selection_type' => $selectionType,
            ]);
        }

        return redirect()->route('admin_appointments.index')->with('success', 'Disponibilidad actualizada correctamente.');
    }

    public function destroyBatch($batch_id)
    {
        AppointmentAvailability::where('batch_id', $batch_id)->delete();
        return response()->json(['success' => true]);
    }
}
