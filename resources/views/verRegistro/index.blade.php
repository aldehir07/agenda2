<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <title>Ver Informacion</title>
</head>

<body>
    @include('plantilla.nabvar')
    <div class="container-fluid px-4">
        <h1 class="text-center my-4">Información General</h1>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-1"></i>
                        Registros de Reservas
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="registrosTable" class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Salón</th>
                                <th>Duración</th>
                                <th>Actividad</th>
                                <th>Analista</th>
                                <th>Depto.</th>
                                <th>N° Evento</th>
                                <th>Scafid</th>
                                <th>Mes</th>
                                <th>Inicio</th>
                                <th>Final</th>
                                <th>Tipo</th>
                                <th>Receso AM</th>
                                <th>Receso PM</th>
                                <th>Publico Meta</th>
                                <th>Participantes</th>
                                <th>Estatus</th>
                                <th>Requisitos</th>
                                <th>Insumos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $reserva)
                                <tr>
                                    <td>{{ $reserva->id }}</td>
                                    <td>{{ $reserva->fecha }}</td>
                                    <td class="{{ [
                                        'Auditorio Jorge L. Quijada' => 'text-white',
                                        'Trabajo en Equipo' => 'bg-success text-white',
                                        'Comunicación Asertiva' => 'bg-info text-white',
                                        'Servicio al Cliente' => 'bg-warning text-dark',
                                        'Integridad' => 'bg-danger text-white',
                                        'Creatividad Innovadora' => 'bg-primary text-white',
                                        'Externo' => 'bg-dark text-white'
                                    ][$reserva->salon] ?? '' }}" @if($reserva->salon=='Auditorio Jorge L. Quijada') style="background-color: purple;" @endif>{{ $reserva->salon }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i A') }}
                                    </td>
                                    <td>{{ $reserva->actividad }}</td>
                                    <td>{{ $reserva->analista }}</td>
                                    <td>{{ $reserva->depto_responsable }}</td>
                                    <td>{{ $reserva->numero_evento }}</td>
                                    <td>{{ $reserva->scafid }}</td>
                                    <td>{{ $reserva->mes }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_final)->format('d/m/Y') }}</td>
                                    <td>{{ $reserva->tipo_actividad }}</td>
                                    <td>
                                        @if($reserva->receso_am)
                                            {{ \Carbon\Carbon::parse($reserva->receso_am)->format('g:i A') }}
                                            <small class="text-muted">(15 min)</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($reserva->receso_pm)
                                            {{ \Carbon\Carbon::parse($reserva->receso_pm)->format('g:i A') }}
                                            <small class="text-muted">(15 min)</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $reserva->publico_meta }}</td>
                                    <td>{{ $reserva->cant_participantes }}</td>
                                    <td>
                                        @php
                                            $estatusClass = [
                                                'Programado' => 'bg-primary',
                                                'Realizado' => 'bg-success',
                                                'Cancelado' => 'bg-danger'
                                            ][$reserva->estatus] ?? 'bg-secondary';

                                            $estatusIcon = [
                                                'Programado' => 'calendar-check',
                                                'Realizado' => 'check-circle',
                                                'Cancelado' => 'times-circle'
                                            ][$reserva->estatus] ?? 'question-circle';
                                        @endphp
                                        <span class="badge {{ $estatusClass }} d-flex align-items-center"
                                              style="font-size: 0.9em; padding: 8px;">
                                            <i class="fas fa-{{ $estatusIcon }} me-2"></i>
                                            {{ $reserva->estatus }}
                                        </span>
                                    </td>
                                    <td>{{ $reserva->requisitos_tecnicos }}</td>
                                    <td>{{ $reserva->insumos }}</td>
                                    <td>
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('reservaCal.edit', $reserva->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
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

    <!-- Modal de Detalles -->
    {{-- <div class="modal fade" id="detallesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Salón:</strong> <span id="modalSalon"></span></p>
                            <p><strong>Actividad:</strong> <span id="modalActividad"></span></p>
                            <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                            <p><strong>Horario:</strong> <span id="modalHorario"></span></p>
                            <p><strong>Analista:</strong> <span id="modalAnalista"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Departamento:</strong> <span id="modalDepto"></span></p>
                            <p><strong>Participantes:</strong> <span id="modalParticipantes"></span></p>
                            <p><strong>Insumos:</strong> <span id="modalInsumos"></span></p>
                            <p><strong>Requisitos Técnicos:</strong> <span id="modalRequisitos"></span></p>
                            <p><strong>Asistencia Técnica:</strong> <span id="modalAsistencia"></span></p>
                            <p><strong>Receso AM:</strong> <span id="modalRecesoAM"></span></p>
                            <p><strong>Receso PM:</strong> <span id="modalRecesoPM"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal de Cambio de Estatus -->
    {{-- <div class="modal fade" id="estatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Estatus de Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cambioEstatusForm">
                        @csrf
                        <input type="hidden" id="reserva_id" name="reserva_id">
                        <div class="mb-4">
                            <label for="estatus" class="form-label">Nuevo Estatus</label>
                            <div class="d-flex gap-2">
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="estatus" id="estatusProgramado" value="Programado">
                                    <label class="form-check-label p-2 rounded border w-100 text-center" for="estatusProgramado">
                                        <i class="fas fa-calendar-check text-primary mb-2 d-block" style="font-size: 1.5em;"></i>
                                        Programado
                                    </label>
                                </div>
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="estatus" id="estatusRealizado" value="Realizado">
                                    <label class="form-check-label p-2 rounded border w-100 text-center" for="estatusRealizado">
                                        <i class="fas fa-check-circle text-success mb-2 d-block" style="font-size: 1.5em;"></i>
                                        Realizado
                                    </label>
                                </div>
                                <div class="form-check flex-fill">
                                    <input class="form-check-input" type="radio" name="estatus" id="estatusCancelado" value="Cancelado">
                                    <label class="form-check-label p-2 rounded border w-100 text-center" for="estatusCancelado">
                                        <i class="fas fa-times-circle text-danger mb-2 d-block" style="font-size: 1.5em;"></i>
                                        Cancelado
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">
                                <i class="fas fa-comment-alt me-1"></i>
                                Observaciones
                            </label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                    placeholder="Ingrese cualquier observación relevante sobre el cambio de estatus..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCambioEstatus()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div> --}}

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
                order: [[0, 'desc']]
            });

            // Manejador para el modal de detalles
            $('.ver-detalles').click(function() {
                var reserva = $(this).data('reserva');
                $('#modalTitulo').text('Detalles de la Reserva #' + reserva.id);
                var detallesHtml = `
                    <p><strong>Fecha:</strong> ${reserva.fecha}</p>
                    <p><strong>Salón:</strong> ${reserva.salon}</p>
                    <p><strong>Actividad:</strong> ${reserva.actividad}</p>
                    <p><strong>Analista:</strong> ${reserva.analista}</p>
                    <p><strong>Departamento:</strong> ${reserva.depto_responsable}</p>
                `;
                $('#modalDetalles').html(detallesHtml);
            });

            // Manejador para eliminar registro
            $('.eliminar-registro').click(function() {
                var id = $(this).data('id');
                if(confirm('¿Está seguro de eliminar este registro?')) {
                    // Aquí iría la lógica para eliminar
                }
            });

            // Tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Manejar el cambio de selección de estatus
        $('.form-check-input[name="estatus"]').change(function() {
            $('.form-check-label').removeClass('border-primary border-2');
            $(this).closest('.form-check-label').addClass('border-primary border-2');
        });
        
    </script>
</body>
</html>
