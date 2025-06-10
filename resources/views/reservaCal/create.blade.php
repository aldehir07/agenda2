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
                                            <input type="date" class="form-control" name="fecha"
                                                value="{{ request('fecha') }}" required readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="depto_responsable" class="form-label">Depto. Responsable</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <select class="form-select" name="depto_responsable" required>
                                                <option value="" disabled selected>Seleccione departamento
                                                </option>
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
                                                <option value="Auditorio Jorge L. Quijada">Auditorio Jorge L. Quijada
                                                </option>
                                                <option value="Trabajo en Equipo">Trabajo en Equipo</option>
                                                <option value="Comunicación Asertiva">Comunicación Asertiva</option>
                                                <option value="Servicio al Cliente">Servicio al Cliente</option>
                                                <option value="Integridad">Integridad</option>
                                                <option value="Creatividad Innovadora">Creatividad Innovadora</option>
                                                <option value="Externo">Externo</option>
                                                <option value="Campus Virtual">Campus Virtual</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="actividad" class="form-label">Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                            <input type="text" class="form-control" name="actividad" value="{{ old('actividad') }}" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_inicio" value="{{ old('hora_inicio') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="time" class="form-control" name="hora_fin" value="{{ old('hora_fin') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Numero Evento</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="number" name="numero_evento" class="form-control" value="{{ old('numero_evento') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Scafid</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input class="form-control" type="text" name="scafid" value="{{ old('scafid') }}">
                                        </div>
                                    </div>

                                    @php
                                    $mesActual = \Carbon\Carbon::now()->locale('es')->monthName; // Obtiene el mes actual en español
                                    $mesActual = ucfirst($mesActual); // Primera letra en mayúscula para que coincida con las opciones
                                    @endphp

                                    <div class="mb-3">
                                        <label class="form-label">Mes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <select class="form-select" name="mes" id="mes" required>
                                                <option value="" disabled {{ old('mes') == '' ? 'selected' : '' }}>Selecciona un mes</option>
                                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $mes)
                                                <option value="{{ $mes }}"
                                                    {{ old('mes') == $mes ? 'selected' : '' }}>
                                                    {{ $mes }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @php
                                        $fechaInicio = old('fecha_inicio', \Carbon\Carbon::now()->format('Y-m-d'));
                                    @endphp

                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio"
                                            class="form-control" value="{{ request('fecha') }}"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fecha Final</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                                            <input class="form-control @error('fecha_final') is-invalid @enderror"
                                                type="date" name="fecha_final"
                                                value="{{ old('fecha_final') }}" required>
                                            @error('fecha_final')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="modalidad" class="form-label">Modalidad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-chalkboard-teacher"></i></span>
                                            <select name="modalidad" id="modalidad" class="form-select" required>
                                                <option value="" disabled>Seleccione modalidad</option>
                                                <option value="Presencial">Presencial</option>
                                                <option value="Virtual">Virtual</option>
                                                <option value="Mixto">Mixto</option>
                                            </select>
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
                                                    <input type="time" class="form-control" name="receso_am" value="{{ old('receso_am') }}">
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
                                                    <input type="time" class="form-control" name="receso_pm" value="{{ old('receso_pm') }}">
                                                    <span class="input-group-text">15 min</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipo_actividad" class="form-label">Tipo de Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <select name="tipo_actividad" id="tipo_actividad" class="form-select" required>
                                                <option value="" disabled selected>Seleccione tipo</option>
                                                <option value="Capacitacion">Capacitacion</option>
                                                <option value="Reunion">Reunión</option>
                                                <option value="REPLICA">REPLICA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subtipo_actividad" class="form-label">Subtipo de Actividad</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                                            <select name="subtipo_actividad" id="subtipo_actividad" class="form-select">
                                                <option value="">Seleccione subtipo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cant_participantes" class="form-label">Cantidad de
                                            Participantes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input type="number" class="form-control" name="cant_participantes" value="{{ old('cant_participantes') }}"
                                                required min="1">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="requisitos_tecnicos" class="form-label">Requisitos
                                            Técnicos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <textarea class="form-control" name="requisitos_tecnicos" rows="2" value="{{ old('requisitos_tecnicos') }}"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="insumos" class="form-label">Insumos Requeridos</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                                            <textarea class="form-control" name="insumos" rows="2" value="{{ old('insumos') }}"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Público Meta</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            <input class="form-control" type="text" name="publico_meta" value="{{ old('publico_meta') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Facilitador o Moderador</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                            <input class="form-control" type="text" name="facilitador_moderador" value="{{ old('facilitador_moderador') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Estatus</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <select class="form-select" name="estatus">
                                                <option value="Programado">Programado</option>
                                                <option value="Realizado">Realizado</option>
                                                <option value="Reprogramado">Reprogramado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Asistencia Técnica</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                            <select class="form-select" name="asistencia_tecnica">
                                                <option value="" disabled>Seleccion una opcion</option>
                                                <option value="Autoridades Superiores">Autoridades Superiores</option>
                                                <option value="Transmision en Vivo">Transmision en Vivo</option>
                                                <option value="Asistencia General">Asistencia General</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Analista</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                            <select class="form-select" name="analista">
                                                <option value="" disabled selected>Seleccion</option>
                                                <option value="Anabel Santana">Anabel Santana</option>
                                                <option value="Eva Ortega">Eva Ortega</option>
                                                <option value="Helvetia Bernal">Helvetia Bernal</option>
                                                <option value="Liseth Rodriguez">Liseth Rodriguez</option>
                                                <option value="Melanie Taylor">Melanie Taylor</option>
                                                <option value="Veronica de Ureña">Veronica de Ureña</option>
                                                <option value="Walter Lizondro">Walter Lizondro</option>
                                                <option value="Yesenia Delgado">Yesenia Delgado</option>
                                            </select>
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

        // Cambiar subtipo de actividad según el tipo seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const tipoActividad = document.getElementById('tipo_actividad');
            const subtipoActividad = document.getElementById('subtipo_actividad');

            const opciones = {
                Capacitacion: ['Seminario', 'Taller', 'Conferencia', 'Seminario/Taller'],
                REPLICA: ['Seminario', 'Taller', 'Seminario/Taller'],
                Reunion: ['Ninguno'] // Puede ser vacío o "Ninguno"
            };

            tipoActividad.addEventListener('change', function() {
                const seleccion = this.value;
                const subtipos = opciones[seleccion] || [];

                // Limpiar opciones actuales
                subtipoActividad.innerHTML = '';

                if (seleccion === 'Reunion') {
                    // Solo opción "Ninguno" y seleccionada automáticamente
                    const option = document.createElement('option');
                    option.value = 'Ninguno';
                    option.textContent = 'Ninguno';
                    option.selected = true;
                    subtipoActividad.appendChild(option);
                } else {
                    // Opción por defecto
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Seleccione subtipo';
                    subtipoActividad.appendChild(defaultOption);

                    // Insertar nuevas opciones
                    subtipos.forEach(function(sub) {
                        const option = document.createElement('option');
                        option.value = sub;
                        option.textContent = sub;
                        subtipoActividad.appendChild(option);
                    });
                }
            });
        });
        // Validar cantidad de participantes según el salón seleccionado
        document.addEventListener('DOMContentLoaded', function() {
            const salonSelect = document.querySelector('select[name="salon"]');
            const participantesInput = document.querySelector('input[name="cant_participantes"]');

            const limites = {
                'Auditorio Jorge L. Quijada': 100,
                'Integridad': 20,
                'Servicio al Cliente': 30,
                'Comunicación Asertiva': 40,
                'Trabajo en Equipo': 30,
                'Creatividad Innovadora': 10
            };

            function validarParticipantes() {
                const salon = salonSelect.value;
                const limite = limites[salon];
                const valor = parseInt(participantesInput.value, 10);
                if (limite && valor > limite) {
                    alert(`El salón "${salon}" tiene un límite de ${limite} participantes.`);
                    participantesInput.value = '';
                }
            }

            salonSelect.addEventListener('change', validarParticipantes);
            participantesInput.addEventListener('input', validarParticipantes);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fechaInput = document.getElementById('fecha_inicio');
            const mesSelect = document.getElementById('mes');
            const meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            function setMesFromFecha() {
                if (fechaInput.value) {
                    const mesIndex = new Date(fechaInput.value).getMonth();
                    mesSelect.value = meses[mesIndex];
                }
            }

            // Al cargar la página, si hay fecha, selecciona el mes correcto
            setMesFromFecha();

            // Cuando el usuario cambie la fecha, actualiza el mes
            fechaInput.addEventListener('change', setMesFromFecha);
        });
    </script>
</body>

</html>
