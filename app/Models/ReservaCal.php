<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReservaCal extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'actividad',
        'analista',
        'salon',
        'depto_responsable',
        'numero_evento',
        'scafid',
        'mes',
        'fecha_inicio',
        'fecha_final',
        'tipo_actividad',
        'subtipo_actividad',
        'modalidad',
        'receso_am',
        'receso_pm',
        'publico_meta',
        'cant_participantes',
        'facilitador_moderador',
        'estatus',
        'cancelado_por',
        'insumos',
        'requisitos_tecnicos',
        'montaje',
        'asistencia_tecnica',
        'creado_por'
    ];
}
