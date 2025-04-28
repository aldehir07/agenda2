<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Sistema de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css')}}">
</head>
<body>
@include('plantilla.nabvar')
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="register-card">
                        <div class="register-header">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-4">
                            <h4>Crear Nueva Cuenta</h4>
                            <p class="text-muted">Completa el formulario para registrarte</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{route('usuario.store')}}" id="registerForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Tu nombre" value="{{ old('name') }}" required>
                                        <label for="name">Nombre Completo</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required>
                                        <label for="email">Correo Electrónico</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password"
                                            name="password" placeholder="Contraseña" required>
                                        <label for="password">Contraseña</label>
                                    </div>
                                    <div class="password-requirements mb-3">
                                        <small>La contraseña debe contener:</small>
                                        <ul>
                                            <li>Mínimo 8 caracteres</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirmar Contraseña" required>
                                        <label for="password_confirmation">Confirmar Contraseña</label>
                                    </div>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary btn-register">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                            </button>

                            <div class="divider">
                                <span>¿Ya tienes una cuenta?</span>
                            </div>

                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-register">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');

        form.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                confirmPassword.value = '';
                confirmPassword.focus();
            }
        });

        // Validación en tiempo real de la contraseña
        password.addEventListener('input', function() {
            const value = this.value;
            const isLongEnough = value.length >= 8;

            // Aquí puedes agregar lógica para mostrar visualmente los requisitos cumplidos
            // Por ejemplo, cambiar el color de los items en la lista
        });
    });
    </script>
</body>
</html>
