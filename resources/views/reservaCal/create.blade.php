<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reservación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    @include('plantilla.nabvar')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Nueva Reservación</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reservaCal.store') }}" method="POST" id="reservaForm">
                    @csrf
                    <div class="row">
                        <!-- Columna Izquierda -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Información Principal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="fecha" class="form-label">Fecha</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" name="fecha" value="{{ request('fecha') }}" required readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="depto_responsable" class="form-label">Depto. Responsable</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <select class="form-select" name="depto_responsable" required>
                                                <option value="" disabled selected>Seleccione departamento</option>
                                                <option value="Presencial">Presencial</option>
                                                <option value="A distancia">A distancia</option>
                                                <option value="Direccion">Dirección</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="salon" class="form-label">Salón</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                            <select name="salon" class="form-select" required>
                                                <option value="" disabled selected>Selecciona un salón</option>
                                                <option value="Auditorio Jorge L. Quijada">Auditorio Jorge L. Quijada</option>
                                                <option value="Trabajo en Equipo">Trabajo en Equipo</option>
                                                <option value="Comunicación Asertiva">Comunicación Asertiva</option>
                                                <option value="Servicio al Cliente">Servicio al Cliente</option>
                                                <option value="Integridad">Integridad</option>
                                                <option value="Creatividad Innovadora">Creatividad Innovadora</option>
                                                <option value="Externo">Externo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="actividad" class="form-label">Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                            <input type="text" class="form-control" name="actividad" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_inicio" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_fin" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Numero Evento</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="number" name="numero_evento" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Scafid</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input class="form-control" type="text" name="scafid">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <select class="form-select" name="mes" required>
                                                <option value="" disabled selected>Selecciona un mes</option>
                                                <option value="Enero">Enero</option>
                                                <option value="Febrero">Febrero</option>
                                                <option value="Marzo">Marzo</option>
                                                <option value="Abril">Abril</option>
                                                <option value="Mayo">Mayo</option>
                                                <option value="Junio">Junio</option>
                                                <option value="Julio">Julio</option>
                                                <option value="Agosto">Agosto</option>
                                                <option value="Septiembre">Septiembre</option>
                                                <option value="Octubre">Octubre</option>
                                                <option value="Noviembre">Noviembre</option>
                                                <option value="Diciembre">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha Inicio</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                            <input class="form-control" type="date" name="fecha_inicio" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha Final</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                                            <input class="form-control" type="date" name="fecha_final" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Detalles del Evento</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="receso_am" class="form-label">Receso AM</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-coffee"></i></span>
                                                    <input type="time" class="form-control" name="receso_am">
                                                    <span class="input-group-text">15 min</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="receso_pm" class="form-label">Receso PM</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-coffee"></i></span>
                                                    <input type="time" class="form-control" name="receso_pm">
                                                    <span class="input-group-text">15 min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_actividad" class="form-label">Tipo de Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <select name="tipo_actividad" class="form-select" required>
                                                <option value="" disabled selected>Seleccione tipo</option>
                                                <option value="Capacitación">Capacitación</option>
                                                <option value="Reunión">Reunión</option>
                                                <option value="REPLICA">REPLICA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cant_participantes" class="form-label">Cantidad de Participantes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input type="number" class="form-control" name="cant_participantes" required min="1">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="requisitos_tecnicos" class="form-label">Requisitos Técnicos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <textarea class="form-control" name="requisitos_tecnicos" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="insumos" class="form-label">Insumos Requeridos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            <textarea class="form-control" name="insumos" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Público Meta</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input class="form-control" type="text" name="publico_meta" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Facilitador o Moderador</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                            <input class="form-control" type="text" name="facilitador_moderador" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Estatus</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <select class="form-select" name="estatus">
                                                <option value="Programado">Programado</option>
                                                <option value="Cancelado">Cancelado</option>
                                                <option value="Realizado">Realizado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Asistencia Técnica</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <select class="form-select" name="asistencia_tecnica">
                                                <option value="Si">Si</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="analista" class="form-label">Analista</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="analista" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Reservación
                            </button>
                            <a href="{{ route('calendario') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación de horas
        const horaInicio = document.querySelector('input[name="hora_inicio"]');
        const horaFin = document.querySelector('input[name="hora_fin"]');
        const recesoAM = document.querySelector('input[name="receso_am"]');
        const recesoPM = document.querySelector('input[name="receso_pm"]');

        function validarHoras() {
            if (horaInicio.value && horaFin.value) {
                if (horaInicio.value >= horaFin.value) {
                    alert('La hora de inicio debe ser menor a la hora de fin');
                    horaFin.value = '';
                }
            }
        }

        function validarRecesos() {
            if (horaInicio.value && horaFin.value) {
                if (recesoAM.value && (recesoAM.value <= horaInicio.value || recesoAM.value >= horaFin.value)) {
                    alert('El receso AM debe estar dentro del horario del evento');
                    recesoAM.value = '';
                }
                if (recesoPM.value && (recesoPM.value <= horaInicio.value || recesoPM.value >= horaFin.value)) {
                    alert('El receso PM debe estar dentro del horario del evento');
                    recesoPM.value = '';
                }
            }
        }

        horaInicio.addEventListener('change', validarHoras);
        horaFin.addEventListener('change', validarHoras);
        recesoAM.addEventListener('change', validarRecesos);
        recesoPM.addEventListener('change', validarRecesos);
    });
    </script>
</body>
</html>
