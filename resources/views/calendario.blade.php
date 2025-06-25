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
        <h2 class="text-center text-white mb-4">CALENDARIO DE RESERVAS</h2>

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
                    <a href="{{ route('calendario', ['vista' => 'mensual', 'mes' => request('mes')]) }}" class="btn btn-{{ request('vista', 'mensual') === 'mensual' ? 'primary' : 'outline-primary' }}">
                        Mensual
                    </a>
                    <a href="{{ route('calendario', ['vista' => 'semanal', 'semana' => request('semana', now()->weekOfYear)]) }}" class="btn btn-{{ request('vista') === 'semanal' ? 'primary' : 'outline-primary' }}">
                        Semanal
                    </a>
                    <a href="{{ route('calendario', ['vista' => 'diaria', 'fecha' => request('fecha', now()->toDateString())]) }}" class="btn btn-{{ request('vista') === 'diaria' ? 'primary' : 'outline-primary' }}">
                        Diaria
                    </a>
                </div>
            </div>

            @if (request('vista', 'mensual') === 'mensual')
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
            @elseif (request('vista') === 'semanal')
                <!-- Selector Semana (input type week) -->
                <form method="GET" action="{{ route('calendario') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="vista" value="semanal">
                    <label for="semana" class="form-label fw-bold mb-0">📅 Semana:</label>
                    <input type="week" id="semana" name="semana"
                           value="{{ request('semana', now()->format('o-\\WW')) }}"
                           class="form-control w-auto" onchange="this.form.submit()">
                </form>
            @elseif (request('vista') === 'diaria')
                <!-- Selector de Fecha (solo visible en vista diaria) -->
                <form method="GET" action="{{ route('calendario') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="vista" value="diaria">
                    <label for="fecha" class="form-label fw-bold mb-0">📅 Fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control w-auto" value="{{ request('fecha', now()->toDateString()) }}" onchange="this.form.submit()">
                </form>
            @endif
        </div>

        <!-- Leyenda de colores de salones -->
        <div class="mb-4 bg-light p-3 rounded shadow">
            <h6 class="fw-bold mb-2">🎨 Color de Salones:</h6>
            <div class="row">
                @php
                    $coloresSalones = [
                    'Auditorio Jorge L. Quijada' => 'text-white',
                    'Trabajo en Equipo' => 'bg-success',
                    'Comunicación Asertiva' => 'bg-info',
                    'Servicio al Cliente' => 'bg-warning',
                    'Integridad' => 'bg-danger',
                    'Creatividad Innovadora' => 'bg-primary',
                    'Externo' => 'bg-dark',
                    'Campus Virtual' => 'bg-secondary',
                    ];
                    $salones = array_keys($coloresSalones);
                @endphp
                @foreach (array_chunk($salones, 4) as $fila)
                    @foreach ($fila as $salon)
                    <div class="col-6 col-md-3 mb-2 d-flex align-items-center">
                        <span class="d-inline-block {{ $coloresSalones[$salon] }}" style="width: 20px; height: 20px; border-radius: 4px; @if ($salon == 'Auditorio Jorge L. Quijada') background-color: purple; @endif"></span>
                        <span class="ms-2">{{ $salon }}</span>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        @php
        // Variables comunes para ambas vistas
            $anioActual = now()->format('Y');
            $mesSeleccionado = request('mes', now()->format('m'));
        @endphp

        @if (request('vista', 'mensual') === 'mensual')
        <!-- Vista Mensual -->
         <div class="mb-3 text-center">
            <button id="btnOcultarCampusMes" class="btn btn-outline-primary btn-sm me-2">Ocultar Campus Virtual</button>
            <button id="btnSoloCampusMes" class="btn btn-outline-secondary btn-sm">Solo Campus Virtual</button>
            <button id="btnMostrarTodosMes" class="btn btn-outline-success btn-sm ms-2">Mostrar Todos</button>
        </div>
        <div class="calendar calendar-month">
            @php
                $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioActual);
                $primerDia = Carbon\Carbon::createFromDate($anioActual, $mesSeleccionado, 1)->dayOfWeek;
                $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            @endphp

            <!-- Encabezados de días de la semana -->
            @foreach ($diasSemana as $dia)
            <div class="calendar__header">{{ $dia }}</div>
            @endforeach

            <!-- Días vacíos antes del primer día del mes -->
            @for ($i = 0; $i < $primerDia; $i++) <div class="calendar__day calendar__day--disabled">
        </div>
        @endfor

        <!-- Días del mes -->
        @for ($i = 1; $i <= $diasEnMes; $i++) @php $fecha=$anioActual . '-' . str_pad($mesSeleccionado, 2, '0' , STR_PAD_LEFT) . '-' . str_pad($i, 2, '0' , STR_PAD_LEFT); $reservasDia=$reservasActivas->filter(function ($reserva) use ($fecha) {
            return $reserva->fecha_inicio <= $fecha && $reserva->fecha_final >= $fecha;
                });
                @endphp
                {{-- Agregar reervas por dia --}}
                <div class="calendar__day">
                    @if (Auth::user()->role === 'admin')
                    <a href="{{ route('reservaCal.create', ['fecha' => $fecha]) }}" class="calendar__date">{{ $i }}</a>
                    @else
                    <span class="calendar__date">{{ $i }}</span>
                    @endif
                    <!-- Vista de reserva -->
                    @foreach ($reservasDia as $reserva)
                    <div class="calendar__task fw-bold {{ $coloresSalones[$reserva->salon] ?? 'bg-light' }} @if ($reserva->salon == 'Auditorio Jorge L. Quijada') text-white @endif" @if ($reserva->salon == 'Auditorio Jorge L. Quijada') style="background-color: purple;" @endif
                        data-reserva-id="{{ $reserva->id }}" data-salon="{{ $reserva->salon }}">
                        {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i') }} -
                        {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i') }}
                    </div>
                    @endforeach
                </div>
        @endfor
        @elseif(request('vista') === 'semanal')
                <div class="mb-3 text-center">
                    <button id="btnOcultarCampusSem" class="btn btn-outline-primary btn-sm me-2">Ocultar Campus Virtual</button>
                    <button id="btnSoloCampusSem" class="btn btn-outline-secondary btn-sm">Solo Campus Virtual</button>
                    <button id="btnMostrarTodosSem" class="btn btn-outline-success btn-sm ms-2">Mostrar Todos</button>
                </div>
                @php
                    $weekInput = request('semana', now()->format('o-\\WW'));
                    // Si viene solo número, anteponer año y 'W'
                    if (strpos($weekInput, '-W') === false) {
                        $weekInput = now()->format('o') . '-W' . $weekInput;
                    }
                    // Parsear el lunes de esa semana ISO (weekday 1)
                    $start = \Carbon\Carbon::parse($weekInput . '-1')->startOfWeek();
                    $days = [];
                    for ($i = 0; $i < 7; $i++) {
                        $days[] = $start->copy()->addDays($i);
                    }
                @endphp
                <!-- VISTA DE LA SEMANA  -->
                <div class="row weekly-view text-white fw-bold">
                    @foreach($days as $day)
                        @php
                            $date = $day->format('Y-m-d');
                            $events = $reservasActivas->filter(fn($r)=> $r->fecha_inicio <= $date && $r->fecha_final >= $date);
                        @endphp
                        <div class="col border p-2 text-white fw-bold" >
                            @php setlocale(LC_TIME, 'es_ES.UTF-8'); @endphp
                            <h6 class="fw-bold">{{ $day->locale('es')->isoFormat('ddd DD/MM') }}</h6>
                            @if($events->isEmpty())
                                <p class="small text-white fw-bold">Sin eventos</p>
                            @else
                                @foreach($events as $r)
                                    <div class="calendar__task small mb-1 p-1 text-white fw-bold {{ $coloresSalones[$r->salon] ?? 'bg-light' }} @if($r->salon==='Auditorio Jorge L. Quijada') text-white @endif"
                                        data-reserva-id="{{ $r->id }}" data-salon="{{ $r->salon }}"
                                        @if($r->salon==='Auditorio Jorge L. Quijada') style="background-color: purple;" @endif>
                                        {{ \Carbon\Carbon::parse($r->hora_inicio)->format('g:i') }} - {{ \Carbon\Carbon::parse($r->hora_fin)->format('g:i') }}<br>{{ $r->actividad }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
        @elseif(request('vista') === 'diaria')
                <!-- Vista Diaria -->
                <div class="daily-view">
                    <h4 class="text-center text-white mb-4 fw-bold">
                        Eventos del dia {{ \Carbon\Carbon::parse($fechaDiaria)->format('d/m/Y') }}
                    </h4>
                    <div class="mb-3 text-center">
                        <button id="btnOcultarCampus" class="btn btn-outline-primary btn-sm me-2">Ocultar Campus Virtual</button>
                        <button id="btnSoloCampus" class="btn btn-outline-secondary btn-sm">Solo Campus Virtual</button>
                        <button id="btnMostrarTodos" class="btn btn-outline-success btn-sm ms-2">Mostrar Todos</button>
                    </div>
                    @if($reservasDiaria->isEmpty())
                        <p class="text-white text-center fw-bold">No hay eventos para esta fecha.</p>
                    @else
                        <div class="row g-3 justify-content-center">
                            @foreach($reservasDiaria as $reserva)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card shadow h-100 border-0" >
                                        <div
                                            class="card-header fw-bold text-white {{ $coloresSalones[$reserva->salon] ?? 'bg-light' }} @if($reserva->salon==='Auditorio Jorge L. Quijada') text-white @endif calendar__task calendar__task--diaria"
                                            @if($reserva->salon==='Auditorio Jorge L. Quijada') style="background-color: purple;" @endif
                                            data-reserva-id="{{ $reserva->id }}"
                                            style="cursor:pointer"
                                        >
                                            {{ $reserva->salon }}
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-2">{{ $reserva->actividad }}</h5>
                                            <p class="mb-1"><strong>Horario:</strong>
                                                {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i A') }}
                                            </p>
                                            <p class="mb-1"><strong>Analista:</strong> {{ $reserva->analista }}</p>
                                            <p class="mb-1"><strong>Estatus:</strong>
                                                <span class="badge
                                                    @if($reserva->estatus=='Programado') bg-primary
                                                    @elseif($reserva->estatus=='Realizado') bg-success
                                                    @elseif($reserva->estatus=='Cancelado') bg-danger
                                                    @elseif($reserva->estatus=='Reprogramado') bg-warning text-dark
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ $reserva->estatus }}
                                                </span>
                                            </p>
                                            <p class="mb-1"><strong>Facilitador:</strong> {{ $reserva->facilitador_moderador }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
        @endif
    </div>

    <!-- Tabla de eventos cancelados -->
    <div class="container mt-5 text-white">
        <h3>Eventos Cancelados</h3>
        <form method="GET" action="{{ route('calendario') }}" class="row g-2 align-items-center mb-3">
            <input type="hidden" name="vista" value="{{ request('vista', 'mensual') }}">
            <div class="col-auto">
                <label for="mes_cancelado" class="col-form-label fw-bold">Filtrar por mes:</label>
            </div>
            <div class="col-auto">
                <select name="mes_cancelado" id="mes_cancelado" class="form-select" onchange="this.form.submit()">
                    @php
                    $mesActual = request('mes_cancelado', now()->format('m'));
                    $meses = [
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre',
                    ];
                    @endphp
                    <option value="">Todos</option>
                    @foreach ($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $mesActual == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <table class="table table-striped text-white">
            <thead>
                <tr>
                    <th>Número Evento</th>
                    <th>Actividad</th>
                    <th>Cancelado por</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Salon</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservasCanceladas as $cancelada)
                    {{-- @dd($cancelada) --}}
                    <tr class="text-white">
                        <td>{{ $cancelada->numero_evento }}</td>
                        <td>{{ $cancelada->actividad }}</td>
                        <td>{{ $cancelada->cancelado_por }}</td>
                        <td>{{ \Carbon\Carbon::parse($cancelada->fecha_inicio)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($cancelada->fecha_final)->format('d/m/Y') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($cancelada->hora_inicio)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($cancelada->hora_fin)->format('g:i A') }}
                        </td>
                        <td class="{{ [
                                'Auditorio Jorge L. Quijada' => 'text-white',
                                'Trabajo en Equipo' => 'bg-success text-white',
                                'Comunicación Asertiva' => 'bg-info text-white',
                                'Servicio al Cliente' => 'bg-warning text-center',
                                'Integridad' => 'bg-danger text-white',
                                'Creatividad Innovadora' => 'bg-primary text-center',
                                'Externo' => 'bg-dark text-white'
                                ][$cancelada->salon] ?? '' }}" @if($cancelada->salon == 'Auditorio Jorge L. Quijada') style="background-color: purple;" @endif>
                            {{ $cancelada->salon }}
                        </td>
                        <td>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('reservaCal.edit', $cancelada->id) }}" class="btn btn-primary btn-sm mb-1">
                                <i class="fas fa-edit"></i> Editar
                        </a>
                            <form action="{{ route('reservaCal.restaurar', $cancelada->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-undo"></i> Restaurar
                                </button>
                            </form>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                            <p><strong>Horario:</strong> <span id="modalHorario"></span></p>
                            <p><strong>Actividad:</strong> <span id="modalActividad"></span></p>
                            <p><strong>Analista:</strong> <span id="modalAnalista"></span></p>
                            <p><strong>Creado por:</strong> <span id="modalUsuario"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha inicio:</strong> <span id="modalFechaInicio"></span></p>
                            <p><strong>Fecha final:</strong> <span id="modalFechaFinal"></span></p>
                            <p><strong>Participantes:</strong> <span id="modalParticipantes"></span></p>
                            <p><strong>Facilitador:</strong> <span id="modalFacilitador"></span></p>
                            <p><strong>Estatus:</strong> <span id="modalEstatus"></span></p>
                        </div>
                        <div class="col-12">
                            <p><strong>Insumos:</strong> <span id="modalInsumos"></span></p>
                            <p><strong>Requisitos Técnicos:</strong> <span id="modalRequisitos"></span></p>
                            <p><strong>Montaje:</strong> <span id="modalMontaje"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if(Auth::user()->role === 'admin')
                        <a href="#" id="modalEditarBtn" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form id="modalCancelForm" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" id="modalCancelarBtn" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este evento?')">
                                <i class="fas fa-ban"></i> Cancelar
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reservas = Object.values(@json($reservaCals));
            const currentUser = @json(Auth::user()->name);
            const coloresSalones = @json($coloresSalones);
            const modal = new bootstrap.Modal(document.getElementById('detalleReservaModal'));


            function mostrarDetallesReserva(reservaId) {
				const reserva = reservas.find(r => r.id === reservaId);
				if (!reserva) return;

				document.getElementById('modalSalon').textContent = reserva.salon;
				document.getElementById('modalColorSalon').className = coloresSalones[reserva.salon] || 'bg-light';
				document.getElementById('modalHorario').textContent =
					`${moment(reserva.hora_inicio, 'HH:mm:ss').format('h:mm')} - ${moment(reserva.hora_fin, 'HH:mm:ss').format('h:mm')}`;
                document.getElementById('modalFechaInicio').textContent = moment(reserva.fecha_inicio).format('DD/MM/YYYY');
                document.getElementById('modalFechaFinal').textContent = moment(reserva.fecha_final).format('DD/MM/YYYY');
				document.getElementById('modalActividad').textContent = reserva.actividad;
				document.getElementById('modalAnalista').textContent = reserva.analista;
                document.getElementById('modalUsuario').textContent = reserva.creado_por || 'N/A';
				
				document.getElementById('modalParticipantes').textContent = reserva.cant_participantes;
				document.getElementById('modalFacilitador').textContent = reserva.facilitador_moderador;
				document.getElementById('modalEstatus').textContent = reserva.estatus;
				document.getElementById('modalInsumos').textContent = reserva.insumos || 'No especificado';
				document.getElementById('modalRequisitos').textContent = reserva.requisitos_tecnicos || 'No especificado';

				// Solo para admin existen estos elementos
				const editarBtn = document.getElementById('modalEditarBtn');
				if (editarBtn) editarBtn.href = `/reservaCal/${reserva.id}/edit`;

				const cancelForm = document.getElementById('modalCancelForm');
				if (cancelForm) cancelForm.action = `/reservaCal/${reserva.id}/cancel`;

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

            // Filtro para la vista diaria
            if (document.getElementById('btnOcultarCampus')) {
                const btnOcultar = document.getElementById('btnOcultarCampus');
                const btnSolo = document.getElementById('btnSoloCampus');
                const btnTodos = document.getElementById('btnMostrarTodos');

                btnOcultar.addEventListener('click', function() {
                    document.querySelectorAll('.daily-view .card-header').forEach(card => {
                        if (card.textContent.trim() === 'Campus Virtual') {
                            card.closest('.col-12').style.display = 'none';
                        } else {
                            card.closest('.col-12').style.display = '';
                        }
                    });
                });

                btnSolo.addEventListener('click', function() {
                    document.querySelectorAll('.daily-view .card-header').forEach(card => {
                        if (card.textContent.trim() === 'Campus Virtual') {
                            card.closest('.col-12').style.display = '';
                        } else {
                            card.closest('.col-12').style.display = 'none';
                        }
                    });
                });

                btnTodos.addEventListener('click', function() {
                    document.querySelectorAll('.daily-view .col-12').forEach(card => {
                        card.style.display = '';
                    });
                });
            }

            // Filtro para la vista mensual
            if (document.getElementById('btnOcultarCampusMes')) {
                const btnOcultarMes = document.getElementById('btnOcultarCampusMes');
                const btnSoloMes = document.getElementById('btnSoloCampusMes');
                const btnTodosMes = document.getElementById('btnMostrarTodosMes');

                btnOcultarMes.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-month .calendar__task').forEach(task => {
                        if (task.getAttribute('data-salon') === 'Campus Virtual') {
                            task.style.display = 'none';
                        } else {
                            task.style.display = '';
                        }
                    });
                });

                btnSoloMes.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-month .calendar__task').forEach(task => {
                        if (task.getAttribute('data-salon') === 'Campus Virtual') {
                            task.style.display = '';
                        } else {
                            task.style.display = 'none';
                        }
                    });
                });

                btnTodosMes.addEventListener('click', function() {
                    document.querySelectorAll('.calendar-month .calendar__task').forEach(task => {
                        task.style.display = '';
                    });
                });
            }

            // Filtro para la vista semanal
            if (document.getElementById('btnOcultarCampusSem') && document.querySelector('.weekly-view')) {
                const btnOcultarSem = document.getElementById('btnOcultarCampusSem');
                const btnSoloSem = document.getElementById('btnSoloCampusSem');
                const btnTodosSem = document.getElementById('btnMostrarTodosSem');

                btnOcultarSem.addEventListener('click', function() {
                    document.querySelectorAll('.weekly-view .calendar__task').forEach(task => {
                        if (task.getAttribute('data-salon') === 'Campus Virtual') {
                            task.style.display = 'none';
                        } else {
                            task.style.display = '';
                        }
                    });
                });

                btnSoloSem.addEventListener('click', function() {
                    document.querySelectorAll('.weekly-view .calendar__task').forEach(task => {
                        if (task.getAttribute('data-salon') === 'Campus Virtual') {
                            task.style.display = '';
                        } else {
                            task.style.display = 'none';
                        }
                    });
                });

                btnTodosSem.addEventListener('click', function() {
                    document.querySelectorAll('.weekly-view .calendar__task').forEach(task => {
                        task.style.display = '';
                    });
                });
            }
        });

    </script>
</body>

</html>
