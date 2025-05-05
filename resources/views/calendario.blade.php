<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Calendario - Sistema de Reservas</title>
    <link rel="stylesheet" href="{{ asset('css/calendario.css') }}">

</head>

<body>
    @include('plantilla.nabvar')
    <div class="container py-4">
        <h2 class="text-center text-white mb-4">Calendario de Reservas</h2>

        {{-- MENSAJES DE ERROR Y ÉXITO --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 d-flex align-items-center justify-content-center gap-3 bg-light p-3 rounded shadow">
            <!-- Selector de Vista -->
            <div class="me-4">
                <label class="form-label fw-bold mb-0">🗓️ Vista:</label>
                <div class="btn-group" role="group">
                    <a href="{{ route('calendario', ['vista' => 'mensual', 'mes' => request('mes')]) }}"
                       class="btn btn-{{ request('vista', 'mensual') === 'mensual' ? 'primary' : 'outline-primary' }}">
                        Mensual
                    </a>
                    <a href="{{ route('calendario', ['vista' => 'semanal', 'semana' => request('semana', now()->weekOfYear)]) }}"
                       class="btn btn-{{ request('vista', 'mensual') === 'semanal' ? 'primary' : 'outline-primary' }}">
                        Semanal
                    </a>
                </div>
            </div>

            @if(request('vista', 'mensual') === 'mensual')
            <!-- Selector de Mes (solo visible en vista mensual) -->
            <form method="GET" action="{{ route('calendario') }}" class="d-flex align-items-center gap-2">
                <input type="hidden" name="vista" value="mensual">
                <label for="mes" class="form-label fw-bold mb-0">📅 Mes:</label>
                <select name="mes" id="mes" class="form-select w-auto" onchange="this.form.submit()">
                    @php
                        setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain', 'Spanish');
                        $mesActual = request('mes', now()->format('m'));
                    @endphp
                    @foreach (range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $m == $mesActual ? 'selected' : '' }}>
                            {{ ucfirst(strftime('%B', mktime(0, 0, 0, $m, 1))) }}
                        </option>
                    @endforeach
                </select>
            </form>
            @else
            <!-- Selector de Semana (solo visible en vista semanal) -->
            <form method="GET" action="{{ route('calendario') }}" class="d-flex align-items-center gap-3 bg-light p-3 rounded shadow">
                <input type="hidden" name="vista" value="semanal">
                <label for="semana" class="form-label fw-bold mb-0 text-primary">
                    <i class="fas fa-calendar-week me-2"></i> Seleccionar Semana:
                </label>
                <div class="input-group w-auto">
                    <select name="semana" id="semana" class="form-select" onchange="this.form.submit()">
                        @php
                            $semanaActual = request('semana', now()->weekOfYear);
                            $totalSemanas = 52;
                        @endphp
                        @foreach (range(1, $totalSemanas) as $s)
                            <option value="{{ $s }}" {{ $s == $semanaActual ? 'selected' : '' }}>
                                Semana {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            @endif
        </div>

        <!-- Leyenda de colores de salones -->
        <div class="mb-4 bg-light p-3 rounded shadow">
            <h6 class="fw-bold mb-2">🎨 Color de Salones:</h6>
            <div class="d-flex flex-wrap gap-3">
                @php
                    $coloresSalones = [
                        'Auditorio Jorge L. Quijada' => 'text-white',
                        'Trabajo en Equipo' => 'bg-success',
                        'Comunicación Asertiva' => 'bg-info',
                        'Servicio al Cliente' => 'bg-warning',
                        'Integridad' => 'bg-danger',
                        'Creatividad Innovadora' => 'bg-primary',
                        'Externo' => 'bg-dark'
                    ];
                @endphp
                @foreach($coloresSalones as $salon => $color)
                    <div class="d-flex align-items-center">
                        <span class="d-inline-block {{ $color }}" style="width: 20px; height: 20px; border-radius: 4px; @if($salon=='Auditorio Jorge L. Quijada') background-color: purple; @endif"></span>
                         <span class="ms-2">{{ $salon }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @php
            // Variables comunes para ambas vistas
            $anioActual = now()->format('Y');
            $mesSeleccionado = request('mes', now()->format('m'));
        @endphp

        @if(request('vista', 'mensual') === 'mensual')
        <!-- Vista Mensual -->
        <div class="calendar calendar-month">
            @php
                $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioActual);
                $primerDia = Carbon\Carbon::createFromDate($anioActual, $mesSeleccionado, 1)->dayOfWeek;
                $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            @endphp

            <!-- Encabezados de días de la semana -->
            @foreach($diasSemana as $dia)
                <div class="calendar__header">{{ $dia }}</div>
            @endforeach

            <!-- Días vacíos antes del primer día del mes -->
            @for($i = 0; $i < $primerDia; $i++)
                <div class="calendar__day calendar__day--disabled"></div>
            @endfor

            <!-- Días del mes -->
            @for ($i = 1; $i <= $diasEnMes; $i++)
                @php
                    $fecha = $anioActual . '-' . str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $reservasDia = $reservaCals->filter(function ($reserva) use ($fecha) {
                        return $reserva->fecha_inicio <= $fecha && $reserva->fecha_final >= $fecha;
                    });
                @endphp
                {{-- Agregar reervas por dia --}}
                <div class="calendar__day">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('reservaCal.create', ['fecha' => $fecha]) }}" class="calendar__date">{{ $i }}</a>
                    @else
                        <span class="calendar__date">{{ $i }}</span>
                    @endif

                    @foreach ($reservasDia as $reserva)
                        <div class="calendar__task {{ $coloresSalones[$reserva->salon] ?? 'bg-light' }} @if($reserva->salon=='Auditorio Jorge L. Quijada') text-white @endif" @if($reserva->salon=='Auditorio Jorge L. Quijada') style="background-color: purple;" @endif data-reserva-id="{{ $reserva->id }}">
                            {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i') }}
                        </div>
                    @endforeach
                </div>
            @endfor
        @else
        <!-- Vista Semanal -->
        <div class="calendar calendar-week">
            @php
                $semanaActual = request('semana', now()->weekOfYear);
                $fechaInicio = Carbon\Carbon::now()->setISODate($anioActual, $semanaActual)->startOfWeek();
                $diasSemana = [];
                for($i = 0; $i < 7; $i++) {
                    $diasSemana[] = $fechaInicio->copy()->addDays($i);
                }
            @endphp

            <div class="week-grid">
                <!-- Columna de horas -->
                <div class="week-grid__hours">
                    @for($hora = 5; $hora <= 22; $hora++)
                        <div class="week-grid__hour">{{ str_pad($hora, 2, '0', STR_PAD_LEFT) }}:00</div>
                    @endfor
                </div>

                <!-- Columnas de días -->
                @foreach($diasSemana as $dia)
                    <div class="week-grid__day">
                        <div class="week-grid__day-header">
                            {{ $dia->format('D') }}<br>
                            {{ $dia->format('d') }}
                        </div>
                        <div class="week-grid__day-content">
                            @php
                                $reservasDia = $reservaCals->filter(function ($reserva) use ($dia) {
                                    return Carbon\Carbon::parse($reserva->fecha_inicio)->format('Y-m-d') <= $dia->format('Y-m-d') &&
                                           Carbon\Carbon::parse($reserva->fecha_final)->format('Y-m-d') >= $dia->format('Y-m-d');
                                });
                            @endphp
                            @foreach($reservasDia as $reserva)
                                @php
                                    $horaInicio = Carbon\Carbon::parse($reserva->hora_inicio);
                                    $horaFin = Carbon\Carbon::parse($reserva->hora_fin);
                                    $top = ($horaInicio->hour - 6) * 60 + $horaInicio->minute;
                                    $height = ($horaFin->hour - $horaInicio->hour) * 60 + ($horaFin->minute - $horaInicio->minute);
                                @endphp
                                <div class="week-grid__event {{ $coloresSalones[$reserva->salon] ?? 'bg-light' }} @if($reserva->salon=='Auditorio Jorge L. Quijada') text-white @endif"
                                     style="top: {{ $top }}px; height: {{ $height }}px; @if($reserva->salon=='Auditorio Jorge L. Quijada') background-color: purple; @endif" data-reserva-id="{{ $reserva->id }}">
                                    <div class="week-grid__event-content">
                                        <strong>{{ $horaInicio->format('g:i') }} - {{ $horaFin->format('g:i') }}</strong><br>
                                        {{ $reserva->actividad }}<br>
                                        <small>{{ $reserva->salon }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal para detalles de reserva -->
    <div class="modal fade" id="detalleReservaModal" tabindex="-1" aria-labelledby="detalleReservaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleReservaModalLabel">Detalles de la Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center">
                                <span id="modalColorSalon" class="d-inline-block me-2" style="width: 20px; height: 20px; border-radius: 4px;"></span>
                                <h6 class="mb-0" id="modalSalon"></h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                            <p><strong>Horario:</strong> <span id="modalHorario"></span></p>
                            <p><strong>Actividad:</strong> <span id="modalActividad"></span></p>
                            <p><strong>Analista:</strong> <span id="modalAnalista"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Depto.:</strong> <span id="modalDepto"></span></p>
                            <p><strong>Participantes:</strong> <span id="modalParticipantes"></span></p>
                            <p><strong>Facilitador:</strong> <span id="modalFacilitador"></span></p>
                            <p><strong>Estatus:</strong> <span id="modalEstatus"></span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Insumos:</strong> <span id="modalInsumos"></span></p>
                            <p><strong>Requisitos Técnicos:</strong> <span id="modalRequisitos"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Convertir las reservas a un objeto JavaScript
        const reservas = @json($reservaCals);
        const coloresSalones = @json($coloresSalones);
        const modal = new bootstrap.Modal(document.getElementById('detalleReservaModal'));

        // Función para mostrar los detalles en el modal
        function mostrarDetallesReserva(reservaId) {
            const reserva = reservas.find(r => r.id === reservaId);
            if (!reserva) return;

            // Formatear la fecha
            const fecha = new Date(reserva.fecha).toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Actualizar el contenido del modal
            document.getElementById('modalSalon').textContent = reserva.salon;
            document.getElementById('modalColorSalon').className = coloresSalones[reserva.salon] || 'bg-light';
            document.getElementById('modalFecha').textContent = fecha;
            document.getElementById('modalHorario').textContent =
                `${moment(reserva.hora_inicio, 'HH:mm:ss').format('h:mm')} - ${moment(reserva.hora_fin, 'HH:mm:ss').format('h:mm')}`;
            document.getElementById('modalActividad').textContent = reserva.actividad;
            document.getElementById('modalAnalista').textContent = reserva.analista;
            document.getElementById('modalDepto').textContent = reserva.depto_responsable;
            document.getElementById('modalParticipantes').textContent = reserva.cant_participantes;
            document.getElementById('modalFacilitador').textContent = reserva.facilitador_moderador;
            document.getElementById('modalEstatus').textContent = reserva.estatus;
            document.getElementById('modalInsumos').textContent = reserva.insumos || 'No especificado';
            document.getElementById('modalRequisitos').textContent = reserva.requisitos_tecnicos || 'No especificado';

            modal.show();
        }

        // Agregar click listeners a todas las reservas
        document.querySelectorAll('.calendar__task, .week-grid__event').forEach(elemento => {
            elemento.addEventListener('click', function(e) {
                e.preventDefault();
                const reservaId = this.getAttribute('data-reserva-id');
                mostrarDetallesReserva(parseInt(reservaId));
            });
        });
    });
    </script>
</body>
</html>
