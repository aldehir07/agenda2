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
        $reservas = ReservaCal::orderBy('fecha_inicio', 'asc')->get(); // Ordenar por fecha de inicio
        return view('verRegistro.index', compact('reservas'));
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
    public function edit(RegistroReserva $registroReserva)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistroReserva $registroReserva)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroReserva $registroReserva)
    {
        //
    }
}
