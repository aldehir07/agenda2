<nav class="navbar navbar-expand-lg navbar-light bg-gradient fw-bold text-dark mb-2" style="background-color: #e9e9f0; box-shadow: 0 5px 10px rgba(0,0,0,0.1);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('calendario') }}">
            <img src="{{asset('images/SIRAACG.png')}}" alt="logo" class="img-fluid" style="max-height: 40px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(Auth::user())
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('verRegistro.index')}}">
                            <i class="fas fa-book me-1"></i> Agenda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('calendario')}}">
                            <i class="fas fa-calendar-plus me-1"></i> Reservar
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link " href="{{route('tabla2')}}">
                            <i class="fas fa-calendar-plus me-1"></i> Consulta
                        </a>
                    </li> --}}
                @endif
            </ul>

            <ul class="navbar-nav ms-auto">
                @if(!Auth::user())
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('login')}}">
                            <i class="fas fa-sign-in-alt me-1"></i> Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('usuario.create')}}">
                            <i class="fas fa-user-plus me-1"></i> Registrarse
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" aria-disabled="true">{{Auth::user()->name}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('logout')}}">
                            <i class="fas fa-user-plus me-1"></i> Cerrar sesion
                        </a>
                    </li>
                @endif
            </ul>

        </div>
    </div>
</nav>

<style>
    .nav-link {
        transition: color 0.3s ease, background-color 0.3s ease;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    .nav-link:hover {
        color: #3f51b5 !important;
        background-color: rgba(63, 81, 181, 0.1);
        border-radius: 4px;
    }
</style>
