<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reservación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('plantilla.nabvar')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Editar Reservación</h4>
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

                <form action="{{ route('reservaCal.update', $reservaCal->id) }}" method="POST" id="reservaForm">
                    @csrf
                    @method('PUT')
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
                                            <input type="date" class="form-control" name="fecha"
                                                value="{{ old('fecha', $reservaCal->fecha) }}" required readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="depto_responsable" class="form-label">Depto. Responsable</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <select class="form-select" name="depto_responsable" required>
                                                <option value="" disabled selected>Seleccione departamento
                                                </option>
                                                <option value="Presencial"
                                                    {{ $reservaCal->depto_responsable == 'Presencial' ? 'selected' : '' }}>
                                                    Presencial</option>
                                                <option value="A distancia"
                                                    {{ $reservaCal->depto_responsable == 'A distancia' ? 'selected' : '' }}>
                                                    A distancia</option>
                                                <option value="Direccion"
                                                    {{ $reservaCal->depto_responsable == 'Direccion' ? 'selected' : '' }}>
                                                    Dirección</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="salon" class="form-label">Salón</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-door-open"></i></span>
                                            <select name="salon" class="form-select" required>
                                                <option value="" disabled selected>Selecciona un salón</option>
                                                <option value="Auditorio Jorge L. Quijada"
                                                    {{ $reservaCal->salon == 'Auditorio Jorge L. Quijada' ? 'selected' : '' }}>
                                                    Auditorio Jorge L. Quijada</option>
                                                <option value="Trabajo en Equipo"
                                                    {{ $reservaCal->salon == 'Trabajo en Equipo' ? 'selected' : '' }}>
                                                    Trabajo en Equipo</option>
                                                <option value="Comunicación Asertiva"
                                                    {{ $reservaCal->salon == 'Comunicación Asertiva' ? 'selected' : '' }}>
                                                    Comunicación Asertiva</option>
                                                <option value="Servicio al Cliente"
                                                    {{ $reservaCal->salon == 'Servicio al Cliente' ? 'selected' : '' }}>
                                                    Servicio al Cliente</option>
                                                <option value="Integridad"
                                                    {{ $reservaCal->salon == 'Integridad' ? 'selected' : '' }}>
                                                    Integridad</option>
                                                <option value="Creatividad Innovadora"
                                                    {{ $reservaCal->salon == 'Creatividad Innovadora' ? 'selected' : '' }}>
                                                    Creatividad Innovadora</option>
                                                <option value="Externo"
                                                    {{ $reservaCal->salon == 'Externo' ? 'selected' : '' }}>
                                                    Externo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="actividad" class="form-label">Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                            <input type="text" class="form-control" name="actividad"
                                                value="{{ old('actividad', $reservaCal->actividad) }}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_inicio"
                                                        value="{{ old('hora_inicio', $reservaCal->hora_inicio ? date('H:i', strtotime($reservaCal->hora_inicio)) : '') }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_fin"
                                                        value="{{ old('hora_fin', $reservaCal->hora_fin ? date('H:i', strtotime($reservaCal->hora_fin)) : '') }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Numero Evento</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="number" name="numero_evento" class="form-control"
                                                value="{{ old('numero_evento', $reservaCal->numero_evento) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Scafid</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input class="form-control" type="text" name="scafid"
                                                value="{{ old('scafid', $reservaCal->scafid) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <select class="form-select" name="mes" required>
                                                <option value="" disabled selected>Selecciona un mes</option>
                                                <option value="Enero"
                                                    {{ $reservaCal->mes == 'Enero' ? 'selected' : '' }}>Enero</option>
                                                <option value="Febrero"
                                                    {{ $reservaCal->mes == 'Febrero' ? 'selected' : '' }}>Febrero
                                                </option>
                                                <option value="Marzo"
                                                    {{ $reservaCal->mes == 'Marzo' ? 'selected' : '' }}>Marzo</option>
                                                <option value="Abril"
                                                    {{ $reservaCal->mes == 'Abril' ? 'selected' : '' }}>Abril</option>
                                                <option value="Mayo"
                                                    {{ $reservaCal->mes == 'Mayo' ? 'selected' : '' }}>Mayo</option>
                                                <option value="Junio"
                                                    {{ $reservaCal->mes == 'Junio' ? 'selected' : '' }}>Junio</option>
                                                <option value="Julio"
                                                    {{ $reservaCal->mes == 'Julio' ? 'selected' : '' }}>Julio</option>
                                                <option value="Agosto"
                                                    {{ $reservaCal->mes == 'Agosto' ? 'selected' : '' }}>Agosto
                                                </option>
                                                <option value="Septiembre"
                                                    {{ $reservaCal->mes == 'Septiembre' ? 'selected' : '' }}>Septiembre
                                                </option>
                                                <option value="Octubre"
                                                    {{ $reservaCal->mes == 'Octubre' ? 'selected' : '' }}>Octubre
                                                </option>
                                                <option value="Noviembre"
                                                    {{ $reservaCal->mes == 'Noviembre' ? 'selected' : '' }}>Noviembre
                                                </option>
                                                <option value="Diciembre"
                                                    {{ $reservaCal->mes == 'Diciembre' ? 'selected' : '' }}>Diciembre
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha Inicio</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                            <input class="form-control" type="date" name="fecha_inicio"
                                                value="{{ old('fecha_inicio', $reservaCal->fecha_inicio) }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha Final</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="fas fa-calendar-minus"></i></span>
                                            <input class="form-control" type="date" name="fecha_final"
                                                value="{{ old('fecha_final', $reservaCal->fecha_final) }}" required>
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
                                                    <span class="input-group-text"><i
                                                            class="fas fa-coffee"></i></span>
                                                    <input type="time" class="form-control" name="receso_am"
                                                        value="{{ old('receso_am', $reservaCal->receso_am) }}">
                                                    <span class="input-group-text">15 min</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="receso_pm" class="form-label">Receso PM</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-coffee"></i></span>
                                                    <input type="time" class="form-control" name="receso_pm"
                                                        value="{{ old('receso_pm', $reservaCal->receso_pm) }}">
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
                                                <option value="" disabled>Seleccione tipo</option>
                                                <option value="Capacitación" {{ old('tipo_actividad', $reservaCal->tipo_actividad) == 'Capacitación' ? 'selected' : '' }}>
                                                    Capacitación</option>
                                                <option value="Reunión" {{ old('tipo_actividad', $reservaCal->tipo_actividad) == 'Reunión' ? 'selected' : '' }}>
                                                    Reunión</option>
                                                <option value="REPLICA" {{ old('tipo_actividad', $reservaCal->tipo_actividad) == 'REPLICA' ? 'selected' : '' }}>
                                                    REPLICA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cant_participantes" class="form-label">Cantidad de
                                            Participantes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input type="number" class="form-control" name="cant_participantes"
                                                value="{{ old('cant_participantes', $reservaCal->cant_participantes) }}"
                                                required min="1">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="requisitos_tecnicos" class="form-label">Requisitos
                                            Técnicos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <textarea class="form-control" name="requisitos_tecnicos"
                                                value="{{ old('requisitos_tecnicos', $reservaCal->requisitos_tecnicos) }}" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="insumos" class="form-label">Insumos Requeridos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            <textarea class="form-control" name="insumos" value="{{ old('insumos', $reservaCal->insumos) }}" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Público Meta</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input class="form-control" type="text" name="publico_meta"
                                                value="{{ old('publico_meta', $reservaCal->publico_meta) }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Facilitador o Moderador</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                            <input class="form-control" type="text" name="facilitador_moderador"
                                                value="{{ old('facilitador_moderador', $reservaCal->facilitador_moderador) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Estatus</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <select class="form-select" name="estatus">
                                                <option value="Programado"
                                                    {{ $reservaCal->estatus == 'Programado' ? 'selected' : '' }}>
                                                    Programado</option>
                                                <option value="Cancelado"
                                                    {{ $reservaCal->estatus == 'Cancelado' ? 'selected' : '' }}>
                                                    Cancelado</option>
                                                <option value="Realizado"
                                                    {{ $reservaCal->estatus == 'Realizado' ? 'selected' : '' }}>
                                                    Realizado</option>
                                                    <option value="Reprogramado"
                                                    {{ $reservaCal->estatus == 'Reprogramado' ? 'selected' : '' }}>
                                                    Reprogramado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Asistencia Técnica</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <select class="form-select" name="asistencia_tecnica">
                                                <option value="Si" {{ $reservaCal->asistencia_tecnica == 'Si' }}>
                                                    Si</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="analista" class="form-label">Analista</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" name="analista"
                                                value="{{ old('analista', $reservaCal->analista) }}" required>
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
                            <a href="{{ route('verRegistro.index') }}" class="btn btn-secondary">
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

            function mostrarError(campo, mensaje) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-danger mt-1';
                errorDiv.textContent = mensaje;
                campo.parentNode.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 3000); // Elimina el mensaje después de 3 segundos
            }

            function validarRecesos() {
                document.querySelectorAll('.text-danger').forEach(e => e.remove()); // Limpia errores previos
                if (horaInicio.value && horaFin.value) {
                    if (recesoAM.value && (recesoAM.value <= horaInicio.value || recesoAM.value >= horaFin.value)) {
                        mostrarError(recesoAM, 'El receso AM debe estar dentro del horario del evento');
                        recesoAM.value = '';
                    }
                    if (recesoPM.value && (recesoPM.value <= horaInicio.value || recesoPM.value >= horaFin.value)) {
                        mostrarError(recesoPM, 'El receso PM debe estar dentro del horario del evento');
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
