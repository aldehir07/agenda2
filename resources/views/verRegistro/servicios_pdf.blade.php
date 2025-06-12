
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Administrativos - PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; }
        th { background: #f0f0f0; }
        .bg-success { background: #198754; color: #fff; }
        .bg-info { background: #0dcaf0; color: #fff; }
        .bg-warning { background: #ffc107; color: #212529; }
        .bg-danger { background: #dc3545; color: #fff; }
        .bg-primary { background: #0d6efd; color: #fff; }
        .bg-dark { background: #212529; color: #fff; }
        .text-white { color: #fff; }
        .text-dark { color: #212529; }
        .purple { background: purple; color: #fff; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Servicios Administrativos</h2>
    <table>
        <thead>
            <tr>
                <th>Salón</th>
                <th>Inicio</th>
                <th>Final</th>
                <th>Actividad</th>
                <th>Duración</th>
                <th>Estatus</th>
                <th>Receso AM</th>
                <th>Receso PM</th>
                <th>Participantes</th>
                <th>Analista</th>
                <th>Insumos</th>
                <th>Montaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
            <tr>
                @php
                    $salonClass = [
                        'Auditorio Jorge L. Quijada' => 'purple',
                        'Trabajo en Equipo' => 'bg-success text-white',
                        'Comunicación Asertiva' => 'bg-info text-white',
                        'Servicio al Cliente' => 'bg-warning text-dark',
                        'Integridad' => 'bg-danger text-white',
                        'Creatividad Innovadora' => 'bg-primary text-white',
                        'Externo' => 'bg-dark text-white'
                    ][$reserva->salon] ?? '';

                    $estatusClass = [
                        'Programado' => 'bg-primary',
                        'Realizado' => 'bg-success',
                        'Cancelado' => 'bg-danger',
                        'Reprogramado' => 'bg-warning'
                    ][$reserva->estatus] ?? 'bg-secondary';
                @endphp
                <td class="{{ $salonClass }}">{{ $reserva->salon }}</td>
                <td>{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($reserva->fecha_final)->format('d/m/Y') }}</td>
                <td>{{ $reserva->actividad }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i A') }} -
                    {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i A') }}
                </td>
                <td class="{{ $estatusClass }}">{{ $reserva->estatus }}</td>
                <td>
                    @if($reserva->receso_am)
                        {{ \Carbon\Carbon::parse($reserva->receso_am)->format('g:i A') }} (15 min)
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($reserva->receso_pm)
                        {{ \Carbon\Carbon::parse($reserva->receso_pm)->format('g:i A') }} (15 min)
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $reserva->cant_participantes }}</td>
                <td>{{ $reserva->analista }}</td>
                <td>{{ $reserva->insumos }}</td>
                <td>{{ $reserva->montaje }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>