<?php

namespace App\Http\Controllers;

use App\Models\ReservaCal;
use Illuminate\Http\Request;
use App\Models\RegistroReserva;
use Carbon\Carbon;

class ReservaCalController extends Controller
{

    public function index()
    {
        $reservaCals = ReservaCal::orderBy('fecha_inicio', 'asc')->get(); // Ordenar por fecha de inicio
        $vista = request('vista', 'mensual'); // Por defecto vista mensual
        $mesSeleccionado = request('mes', now()->format('m'));
        $anioActual = now()->format('Y');

        if ($vista === 'semanal') {
            $semanaActual = request('semana', now()->weekOfYear);
            $fechaInicio = Carbon::now()->setISODate($anioActual, $semanaActual)->startOfWeek();
            $fechaFin = $fechaInicio->copy()->endOfWeek();

            // Filtrar reservas que coincidan con la semana seleccionada
            $reservaCals = $reservaCals->filter(function ($reserva) use ($fechaInicio, $fechaFin) {
                return Carbon::parse($reserva->fecha_inicio)->between($fechaInicio, $fechaFin) ||
                       Carbon::parse($reserva->fecha_final)->between($fechaInicio, $fechaFin) ||
                       ($reserva->fecha_inicio <= $fechaInicio && $reserva->fecha_final >= $fechaFin);
            });
        }

        return view('calendario', compact('reservaCals', 'vista'));
    }


    public function create(Request $request)
    {
        $fecha = $request->query('fecha');
        return view('reservaCal.create', compact('fecha'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'actividad' => 'required|string|max:255',
            'analista' => 'required|string|max:255',
            'salon' => 'required|string|in:"Auditorio Jorge L. Quijada",
                                "Trabajo en Equipo",
                                "Comunicación Asertiva",
                                "Servicio al Cliente",
                                "Integridad",
                                "Creatividad Innovadora",
                                "Externo"',
            'depto_responsable' => 'required',
            'numero_evento' => 'required|numeric|digits:4',
            'scafid' => 'nullable|string',
            'mes' => 'required|string',
            'tipo_actividad' => 'required',
            'receso_am' => 'nullable',
            'receso_pm' => 'nullable',
            'publico_meta' => 'required|string',
            'cant_participantes' => 'required|numeric',
            'facilitador_moderador' => 'required|string',
            'estatus' => 'required',
            'insumos' => 'nullable|string',
            'requisitos_tecnicos' => 'nullable|string',
            'asistencia_tecnica' => 'required'
        ]);

        // Verificar si ya existe una reserva en el mismo rango de fechas, horas y salón
        $existeReserva = ReservaCal::where('salon', $request->salon)
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_final])
                      ->orWhereBetween('fecha_final', [$request->fecha_inicio, $request->fecha_final]);
            })
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('hora_inicio', '<', $request->hora_fin)
                      ->where('hora_fin', '>', $request->hora_inicio);
                });
            })
            ->exists();

        if ($existeReserva) {
            return redirect()->back()->withErrors(['error' => 'Ya existe una reserva en el mismo salón y horario.']);
        }

        // Crear un único registro con las fechas de inicio y fin
        ReservaCal::create([
            'fecha' => $request->fecha_inicio, // Fecha de inicio como referencia
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'actividad' => $request->actividad,
            'analista' => $request->analista,
            'salon' => $request->salon,
            'depto_responsable' => $request->depto_responsable,
            'numero_evento' => $request->numero_evento,
            'scafid' => $request->scafid,
            'mes' => $request->mes,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_final' => $request->fecha_final,
            'tipo_actividad' => $request->tipo_actividad,
            'receso_am' => $request->receso_am,
            'receso_pm' => $request->receso_pm,
            'publico_meta' => $request->publico_meta,
            'cant_participantes' => $request->cant_participantes,
            'facilitador_moderador' => $request->facilitador_moderador,
            'estatus' => $request->estatus,
            'insumos' => $request->insumos,
            'requisitos_tecnicos' => $request->requisitos_tecnicos,
            'asistencia_tecnica' => $request->asistencia_tecnica
        ]);

        RegistroReserva::create($request->all());

        return redirect()->route('calendario')->with('success', '✅ Reserva creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservaCal $reservaCal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservaCal $reservaCal)
    {
        return view('reservaCal.edit', compact('reservaCal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservaCal $reservaCal)
    {
        // dd($request->all());
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'actividad' => 'required|string|max:255',
            'analista' => 'required|string|max:255',
            'salon' => 'required|string|in:"Auditorio Jorge L. Quijada",
                                "Trabajo en Equipo",
                                "Comunicación Asertiva",
                                "Servicio al Cliente",
                                "Integridad",
                                "Creatividad Innovadora",
                                "Externo"',
            'depto_responsable' => 'required',
            'numero_evento' => 'required|numeric|digits:4',
            'scafid' => 'nullable|string',
            'mes' => 'required|string',
            'tipo_actividad' => 'required',
            'receso_am' => 'nullable',
            'receso_pm' => 'nullable',
            'publico_meta' => 'required|string',
            'cant_participantes' => 'required|numeric',
            'facilitador_moderador' => 'required|string',
            'estatus' => 'required',
            'insumos' => 'nullable|string',
            'requisitos_tecnicos' => 'nullable|string',
            'asistencia_tecnica' => 'required'
        ]);

        // Actualiza la reservación
        $reservaCal->update($request->all());

        return redirect()->route('verRegistro.index')->with('success', 'Reserva actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservaCal $reservaCal)
    {
        //
    }
}
