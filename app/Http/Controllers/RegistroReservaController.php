<?php

namespace App\Http\Controllers;

use App\Models\RegistroReserva;
use App\Models\ReservaCal;
use Illuminate\Http\Request;

class RegistroReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = ReservaCal::orderBy('fecha_inicio', 'asc')->get();

        // Obtener la semana seleccionada o la actual
        $semana = request('semana', now()->format('o-\WW')); // Ejemplo: 2025-W24

        // Calcular fecha de inicio y fin de la semana
        if (str_contains($semana, '-W')) {
            [$anioSemana, $numSemana] = explode('-W', $semana);
        } else {
            $anioSemana = now()->format('o');
            $numSemana = $semana;
        }
        $fechaInicio = \Carbon\Carbon::now()->setISODate((int)$anioSemana, (int)$numSemana)->startOfWeek();
        $fechaFin = $fechaInicio->copy()->endOfWeek();

        // Filtrar reservas de la semana
        $reservasSemana = $reservas->filter(function ($reserva) use ($fechaInicio, $fechaFin) {
            return \Carbon\Carbon::parse($reserva->fecha_inicio)->between($fechaInicio, $fechaFin) ||
                \Carbon\Carbon::parse($reserva->fecha_final)->between($fechaInicio, $fechaFin) ||
                ($reserva->fecha_inicio <= $fechaInicio && $reserva->fecha_final >= $fechaFin);
        });

        return view('verRegistro.index', compact('reservas', 'reservasSemana', 'semana'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistroReserva $registroReserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroReserva $registroReserva) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservaCal $reservaCal) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroReserva $registroReserva)
    {
        //
    }
}
