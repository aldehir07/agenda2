<?php

namespace App\Http\Controllers;

use App\Models\ReservaCal;
use Illuminate\Http\Request;
use App\Models\RegistroReserva;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservaCalController extends Controller
{

    public function index()
    {
        $reservaCals = ReservaCal::orderBy('fecha_inicio', 'asc')->get(); // Ordenar por fecha de inicio
        $vista = request('vista', 'mensual'); // Por defecto vista mensual
        $mesSeleccionado = request('mes', now()->format('m'));
        $anioActual = now()->format('Y');

        //Filtrar las reservas activas(no canceladas)
        $reservasActivas = $reservaCals->filter(function ($reserva){
            return strtolower(trim($reserva->estatus)) !== 'cancelado';
        });
        //Filtrar las reservas canceladas
        $reservasCanceladas = $reservaCals->filter(function ($reserva){
            return strtolower(trim($reserva->estatus)) === 'cancelado';
        });

        // Filtro por mes para eventos cancelados
        $mesCancelado = request('mes_cancelado');
        if ($mesCancelado) {
            $reservasCanceladas = $reservasCanceladas->filter(function ($reserva) use ($mesCancelado) {
                return \Carbon\Carbon::parse($reserva->fecha_inicio)->format('m') == $mesCancelado;
            });
        }

        if ($vista === 'semanal') {
            // Parsear semana tipo 'YYYY-Www' o número simple
            $weekInput = request('semana', now()->format('o-\WW')); // ej '2025-W24'
            if (str_contains($weekInput, '-W')) {
                [$anioSemana, $numSemana] = explode('-W', $weekInput);
            } else {
                $anioSemana = now()->format('o');
                $numSemana = $weekInput;
            }
            $fechaInicio = Carbon::now()->setISODate((int)$anioSemana, (int)$numSemana)->startOfWeek();
            $fechaFin = $fechaInicio->copy()->endOfWeek();

            // Filtrar reservas que coincidan con la semana seleccionada
            $reservaCals = $reservaCals->filter(function ($reserva) use ($fechaInicio, $fechaFin) {
                return Carbon::parse($reserva->fecha_inicio)->between($fechaInicio, $fechaFin) ||
                       Carbon::parse($reserva->fecha_final)->between($fechaInicio, $fechaFin) ||
                       ($reserva->fecha_inicio <= $fechaInicio && $reserva->fecha_final >= $fechaFin);
            });
        }

        // FILTRAR reservas semanales
        if ($vista === 'semanal') {
            // Parsear semana tipo 'YYYY-Www' o número simple
            $weekInput = request('semana', now()->format('o-\WW')); // ej '2025-W24'
            if (str_contains($weekInput, '-W')) {
                [$anioSemana, $numSemana] = explode('-W', $weekInput);
            } else {
                $anioSemana = now()->format('o');
                $numSemana = $weekInput;
            }
            $fechaInicio = Carbon::now()->setISODate((int)$anioSemana, (int)$numSemana)->startOfWeek();
            $fechaFin = $fechaInicio->copy()->endOfWeek();

            $reservaCals = $reservaCals->filter(function ($reserva) use ($fechaInicio, $fechaFin) {
                return Carbon::parse($reserva->fecha_inicio)->between($fechaInicio, $fechaFin)
                    || Carbon::parse($reserva->fecha_final)->between($fechaInicio, $fechaFin)
                    || ($reserva->fecha_inicio <= $fechaInicio && $reserva->fecha_final >= $fechaFin);
            });
        }

        // FILTRAR eventos de un día específico
        if ($vista === 'diaria') {
            $fechaDiaria = request('fecha', now()->toDateString());
            $reservasDiaria = $reservasActivas->filter(function ($reserva) use ($fechaDiaria) {
                return Carbon::parse($reserva->fecha_inicio)->format('Y-m-d') <= $fechaDiaria
                    && Carbon::parse($reserva->fecha_final)->format('Y-m-d') >= $fechaDiaria;
            });

            return view('calendario', compact(
                'reservaCals',
                'reservasCanceladas',
                'reservasActivas',
                'vista',
                'reservasDiaria',
                'fechaDiaria'
            ));
        }

        return view('calendario', compact('reservaCals', 'reservasCanceladas', 'reservasActivas', 'vista'));  
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
            'analista' => 'required|string|max:20',
            'salon' => 'required|string|in:"Auditorio Jorge L. Quijada",
                                "Trabajo en Equipo",
                                "Comunicación Asertiva",
                                "Servicio al Cliente",
                                "Integridad",
                                "Creatividad Innovadora",
                                "Externo",
                                "Campus Virtual"',
            'depto_responsable' => 'required',
            'numero_evento' => 'required|numeric|unique:reserva_cals,numero_evento',
            'scafid' => 'nullable|string',
            'mes' => 'required|string',
            'tipo_actividad' => 'required|in:Reunion,Capacitacion,REPLICA',
            'subtipo_actividad' => [
                'nullable',
                function($attribute, $value, $fail) use ($request) {
                    $tipo = $request->input('tipo_actividad');

                    $opciones = [
                        'Capacitacion' => ['Seminario', 'Taller', 'Conferencia', 'Seminario/Taller'],
                        'REPLICA' => ['Seminario', 'Taller', 'Seminario/Taller'],
                        'Reunion' => ['Ninguno', null],
                    ];
                    if(!in_array($value, $opciones[$tipo])){
                        $fail("El subtipo de actividad '$value' no es valido para el tipo '$tipo'.");
                    }
                }
            ],
            'receso_am' => 'nullable',
            'receso_pm' => 'nullable',
            'modalidad' => 'required|in:Presencial,Virtual,Mixto',
            'publico_meta' => 'required|string',
            'cant_participantes' => [
                'required', 'numeric',
                function ($attribute, $value) use ($request) {
                    $limites = [
                        'Auditorio Jorge L. Quijada' => 100,
                        'Integridad' => 20,
                        'Servicio al Cliente' => 30,
                        'Comunicación Asertiva' => 40,
                        'Trabajo en Equipo' => 30,
                        'Creatividad Innovadora' => 10,
                    ];
                    $salon = $request->input('salon');
                    if (isset($limites[$salon]) && $value > $limites[$salon]) {
                        return back()->withErrors([$attribute => "El salón '$salon' tiene un límite de {$limites[$salon]} participantes."]);
                    }
                }
            ],
            'facilitador_moderador' => 'required|string',
            'estatus' => 'required',
            'insumos' => 'nullable|string',
            'requisitos_tecnicos' => 'nullable|string',
            'asistencia_tecnica' => 'required'
        ]);

        // Verificar si ya existe una reserva en el mismo rango de fechas, horas y salón
        $existeReserva = ReservaCal::where('salon', $request->salon)
            ->where('estatus', '!=', 'Cancelado') //Ignora reservas canceladas
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
            'subtipo_actividad' => $request->subtipo_actividad,
            'modalidad' => $request->modalidad,
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

    //FUNCION PARA VER EL REGISTRO DE RESERVA
    public function show($id)
    {
        $reserva = ReservaCal::findOrFail($id);
        return view('reservaCal.show', compact('reserva'));
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
            'analista' => 'required|string|max:20',
            'salon' => 'required|string|in:"Auditorio Jorge L. Quijada",
                                "Trabajo en Equipo",
                                "Comunicación Asertiva",
                                "Servicio al Cliente",
                                "Integridad",
                                "Creatividad Innovadora",
                                "Externo",
                                "Campus Virtual"',
            'depto_responsable' => 'required',
            'numero_evento' => 'required|numeric|unique:reserva_cals,numero_evento,'. $reservaCal->id,
            'scafid' => 'nullable|string',
            'mes' => 'required|string',
            'tipo_actividad' => 'required|in:Reunion,Capacitacion,REPLICA',
            'subtipo_actividad' => [
                'nullable',
                function($attribute, $value, $fail) use ($request) {
                    $tipo = $request->input('tipo_actividad');

                    $opciones = [
                        'Capacitacion' => ['Seminario', 'Taller', 'Conferencia', 'Seminario/Taller'],
                        'REPLICA' => ['Seminario', 'Taller', 'Seminario/Taller'],
                        'Reunion' => ['Ninguno', null],
                    ];
                    if(!in_array($value, $opciones[$tipo])){
                        $fail("El subtipo de actividad '$value' no es valido para el tipo '$tipo'.");
                    }
                }
            ],
            'receso_am' => 'nullable',
            'receso_pm' => 'nullable',
            'modalidad' => 'required|in:Presencial,Virtual,Mixto',
            'publico_meta' => 'required|string',
            'cant_participantes' => [
                'required', 'numeric',
                function ($attribute, $value) use ($request) {
                    $limites = [
                        'Auditorio Jorge L. Quijada' => 100,
                        'Integridad' => 20,
                        'Servicio al Cliente' => 30,
                        'Comunicación Asertiva' => 40,
                        'Trabajo en Equipo' => 30,
                        'Creatividad Innovadora' => 10,
                    ];
                    $salon = $request->input('salon');
                    if (isset($limites[$salon]) && $value > $limites[$salon]) {
                        return back()->withErrors([$attribute => "El salón '$salon' tiene un límite de {$limites[$salon]} participantes."]);
                    }
                }
            ],
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

    //FUNCION PARA CANCELAR RESERVAS
    public function cancel(ReservaCal $reservaCal){
        //Actualizar el estatus a 'cancelado'
        $reservaCal->update([
            'estatus'       => 'Cancelado',
            'cancelado_por' => Auth::user()->name,
        ]);
        return redirect()->route('calendario')
                         ->with('success','Reserva cancelada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservaCal $reservaCal)
    {
        $reservaCal->delete();
        return redirect()->route('calendario')->with('success', 'Reserva elimanada exitosamente.');
    }
}
