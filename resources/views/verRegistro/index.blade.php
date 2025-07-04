<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <title>Ver Informacion</title>
</head>

<body>
    @include('plantilla.nabvar')
    <div class="container-fluid px-4">
        <h1 class="text-center my-4">Información General</h1>
        <form method="GET" class="mb-3 text-center" id="filtroForm">
            <label for="semana" class="form-label fw-bold">Ver solo eventos de la semana:</label>
            <input type="week" id="semana" name="semana" value="{{ $semana }}" class="form-control d-inline-block w-auto" onchange="this.form.submit()">
            <input type="hidden" name="search" id="searchInput" value="{{ request('search', '') }}">
            <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
            @if(request()->has('semana'))
                <a href="{{ route('verRegistro.index') }}" class="btn btn-secondary ms-2">Ver todos</a>
            @endif
        </form>
        <a href="#" id="exportPdfBtn" class="btn btn-danger mb-3" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar Servicios Administrativos a PDF
        </a>
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-1"></i>
                        Registros de Reservas
                    </div>
                </div>
            </div>

            <!-- Selector para cambiar la vista -->
            <div class="mt-2 text-center">
                <label for="viewSelector" class="form-label fw-bold">Seleccionar Vista:</label>
                <select id="viewSelector" class="form-select w-auto d-inline-block">
                    <option value="general">Vista General</option>
                    <option value="soporte">Soporte Técnico</option>
                    <option value="insumos">Servicios Administrativos</option>
                    <option value="virtual">Virtual</option>
                </select>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="registrosTable" class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr class="text-center">
                                {{-- <th scope="col" data-column="id">ID</th>
                                <th scope="col" data-column="created_at">F. Crear</th> --}}
                                <th scope="col" data-column="salon">Salón</th>
                                <th scope="col" data-column="fecha_inicio">Inicio</th>
                                <th scope="col" data-column="fecha_final">Final</th>
                                <th scope="col" data-column="duracion">Duración</th>
                                <th scope="col" data-column="receso_am">Receso AM</th>
                                <th scope="col" data-column="receso_pm">Receso PM</th>
                                <th scope="col" data-column="analista">Analista</th>
								<th scope="col" data-column="facilitador_moderador">Facilitador</th>
                                <th scope="col" data-column="estatus">Estatus</th>
                                <th scope="col" data-column="cant_participantes">Participantes</th>
                                <th scope="col" data-column="actividad">Actividad</th>
                                <th scope="col" data-column="depto_responsable">Depto.</th>
                                <th scope="col" data-column="numero_evento">N° Evento</th>
                                <th scope="col" data-column="scafid">Scafid</th>
                                <th scope="col" data-column="mes">Mes</th>
                                <th scope="col" data-column="tipo_actividad">Tipo</th>
                                <th scope="col" data-column="subtipo_actividad">Subtipo</th>
                                <th scope="col" data-column="publico_meta">Publico Meta</th>
                                <th scope="col" data-column="requisitos_tecnicos">Requisitos</th>
                                <th scope="col" data-column="insumos">Insumos</th>
                                <th scope="col" data-column="montaje">Montaje</th> 
                                <th scope="col" data-column="asistencia_tecnica">asistencia_tecnica</th>
                                <th scope="col" data-column="acciones">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mostrarSemana = request()->has('semana');
                                $lista = $mostrarSemana ? $reservasSemana : $reservas;
                            @endphp
                            @foreach ($lista as $reserva)
                            <tr>
                                {{-- <th scope="row" data-column="id">{{ $reserva->id }}</td>
                                <td data-column="created_at">{{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y') }}</td> --}}
                                <td data-column="salon" class="{{ [
                                    'Auditorio Jorge L. Quijada' => 'text-white',
                                    'Trabajo en Equipo' => 'bg-success text-white',
                                    'Comunicación Asertiva' => 'bg-info text-white',
                                    'Servicio al Cliente' => 'bg-warning text-dark',
                                    'Integridad' => 'bg-danger text-white',
                                    'Creatividad Innovadora' => 'bg-primary text-white',
                                    'Externo' => 'bg-dark text-white'
                                ][$reserva->salon] ?? '' }}" @if($reserva->salon=='Auditorio Jorge L. Quijada') style="background-color: purple;" @endif>{{ $reserva->salon }}</td>
                                <td data-column="fecha_inicio">{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                                <td data-column="fecha_final">{{ \Carbon\Carbon::parse($reserva->fecha_final)->format('d/m/Y') }}</td>
                                <td data-column="duracion">
                                    {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i A') }}
                                </td>
                                <td data-column="receso_am">
                                    @if($reserva->receso_am)
                                    {{ \Carbon\Carbon::parse($reserva->receso_am)->format('g:i A') }}
                                    <small class="text-muted">(15 min)</small>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td data-column="receso_pm">
                                    @if($reserva->receso_pm)
                                    {{ \Carbon\Carbon::parse($reserva->receso_pm)->format('g:i A') }}
                                    <small class="text-muted">(15 min)</small>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td data-column="analista">{{ $reserva->analista }}</td>
								<td data-column="facilitador_moderador">{{ $reserva->facilitador_moderador }}</td>
                                <td data-column="estatus">
                                    @php
                                    $estatusClass = [
                                    'Programado' => 'bg-primary',
                                    'Realizado' => 'bg-success',
                                    'Cancelado' => 'bg-danger',
                                    'Reprogramado' => 'bg-warning' // Nuevo estado agregado
                                    ][$reserva->estatus] ?? 'bg-secondary';

                                    $estatusIcon = [
                                    'Programado' => 'calendar-check',
                                    'Realizado' => 'check-circle',
                                    'Cancelado' => 'times-circle',
                                    'Reprogramado' => 'exclamation-triangle' // Nueva opción de ícono
                                    ][$reserva->estatus] ?? 'question-circle';
                                    @endphp
                                    <span class="badge {{ $estatusClass }} d-flex align-items-center"
                                        style="font-size: 0.9em; padding: 8px;">
                                        <i class="fas fa-{{ $estatusIcon }} me-2"></i>
                                        {{ $reserva->estatus }}
                                    </span>
                                </td>
                                <td data-column="cant_participantes">{{ $reserva->cant_participantes }}</td>
                                <td data-column="actividad">{{ $reserva->actividad }}</td>
                                <td data-column="depto_responsable">{{ $reserva->depto_responsable }}</td>
                                <td data-column="numero_evento">{{ $reserva->numero_evento }}</td>
                                <td data-column="scafid">{{ $reserva->scafid }}</td>
                                <td data-column="mes">
                                    {{ \Carbon\Carbon::parse($reserva->fecha_inicio)->locale('es')->isoFormat('MMMM') }}
                                </td>
                                <td data-column="tipo_actividad">{{ $reserva->tipo_actividad }}</td>
                                <td data-column="subtipo_actividad">{{ $reserva->subtipo_actividad ?? 'N/A' }}</td>
                                <td data-column="publico_meta">{{ $reserva->publico_meta }}</td>
                                <td data-column="requisitos_tecnicos">{{ $reserva->requisitos_tecnicos }}</td>
                                <td data-column="insumos">{{ $reserva->insumos }}</td>
                                <td data-column="montaje">{{ $reserva->montaje }}</td>
                                <td data-column="asistencia_tecnica">{{ $reserva->asistencia_tecnica }}</td>
                                <td data-column="acciones">
                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('reservaCal.edit', $reserva->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                        @if (Auth::user()->name === 'aldehir') 
                                            <form action="{{ route('reservaCal.destroy', $reserva->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#registrosTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                pageLength: 10,
                order: [
                    [0, 'desc']
                ]
            });

             // Al hacer clic en exportar PDF, toma el valor del buscador y redirige con los parámetros
            $('#exportPdfBtn').on('click', function(e) {
                e.preventDefault();
                let semana = $('#semana').val();
                let search = $('.dataTables_filter input').val();
                let url = "{{ route('servicios.pdf') }}?semana=" + encodeURIComponent(semana) + "&search=" + encodeURIComponent(search);
                window.open(url, '_blank');
            });


            // Selector de vista para mostrar/ocultar columnas
            document.getElementById("viewSelector").addEventListener("change", function() {
                let selectedView = this.value;
                let allColumns = document.querySelectorAll("[data-column]");

                // Mostrar todas las filas SIEMPRE antes de aplicar el filtro
                document.querySelectorAll("#registrosTable tbody tr").forEach(row => {
                    row.style.display = "";
                });

                allColumns.forEach(col => col.style.display = "none"); // Ocultar todo por defecto

                if (selectedView === "general") {
                    allColumns.forEach(col => col.style.display = "table-cell"); // Mostrar todo
                    // Mostrar todas las filas (ya hecho arriba)
                } else if (selectedView === "soporte") {
                    let soporteColumns = ["salon", "fecha_inicio", "fecha_final", "actividad", "duracion", "analista", "estatus", "requisitos_tecnicos", "asistencia"];
                    soporteColumns.forEach(col => {
                        document.querySelectorAll(`[data-column="${col}"]`).forEach(el => el.style.display = "table-cell");
                    });

                    // Ocultar filas con salón "Campus Virtual"
                    document.querySelectorAll("#registrosTable tbody tr").forEach(row => {
                        let salonCell = row.querySelector('[data-column="salon"]');
                        if (salonCell) {
                            let salon = salonCell.textContent.trim();
                            if (salon === "Campus Virtual") {
                                row.style.display = "none";
                            }
                        }
                    });

                } else if (selectedView === "virtual") {
                    let soporteColumns = ["fecha_inicio", "fecha_final", "actividad", "numero_evento", "scafid", "duracion", "analista", "estatus"];
                    soporteColumns.forEach(col => {
                        document.querySelectorAll(`[data-column="${col}"]`).forEach(el => el.style.display = "table-cell");
                    });
                    // Mostrar todas las filas (ya hecho arriba)
                } else if (selectedView === "insumos") {
                    let insumosColumns = ["salon", "fecha_inicio", "fecha_final", "actividad", "duracion", "estatus", "receso_am", "receso_pm", "cant_participantes", "analista", "insumos", "montaje"];
                    insumosColumns.forEach(col => {
                        document.querySelectorAll(`[data-column="${col}"]`).forEach(el => el.style.display = "table-cell");
                    });

                    // Ocultar filas con salón "Campus Virtual" o "Externo"
                    document.querySelectorAll("#registrosTable tbody tr").forEach(row => {
                        let salonCell = row.querySelector('[data-column="salon"]');
                        if (salonCell) {
                            let salon = salonCell.textContent.trim();
                            if (salon === "Campus Virtual") {
                                row.style.display = "none";
                            }
                        }
                    });
                }
            });
        });

        // Manejar el cambio de selección de estatus
        $('.form-check-input[name="estatus"]').change(function() {
            $('.form-check-label').removeClass('border-primary border-2');
            $(this).closest('.form-check-label').addClass('border-primary border-2');
        });
    </script>
</body>

</html>