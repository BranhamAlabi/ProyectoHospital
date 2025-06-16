<nav class="navbar navbar-expand-lg navbar-light meditech-navbar-paciente">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('gestion.inicioPaciente') }}">
            <img src="{{ asset('image/meditech_logo.png') }}" alt="MediTech Logo" class="navbar-logo me-2">
            <div class="brand-text">
                <span class="brand-title">MediTech</span>
                <small class="brand-subtitle d-block">Portal del Paciente</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPaciente" aria-controls="navbarPaciente" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPaciente">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gestion.inicioPaciente') ? 'active' : '' }}" href="{{ route('gestion.inicioPaciente') }}">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('paciente.citas.*') ? 'active' : '' }}" href="{{ route('paciente.citas.index') }}">
                        <i class="fas fa-calendar-check me-1"></i>Mis Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('paciente.expedientes') ? 'active' : '' }}" href="{{ route('paciente.expedientes') }}">
                        <i class="fas fa-file-medical me-1"></i>Mis Expedientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('paciente.expediente.personal') ? 'active' : '' }}" href="{{ route('paciente.expediente.personal') }}">
                        <i class="fas fa-user-injured me-1"></i>Expediente Personal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}" href="{{ route('perfil.show') }}">
                        <i class="fas fa-user-circle me-1"></i>Mi Perfil
                    </a>
                </li>
            </ul>
            
            <!-- Usuario actual -->
            <div class="navbar-text me-3 d-none d-md-block">
                <i class="fas fa-user me-1"></i>
                <span class="text-primary">{{ session('usuario_nombre', 'Usuario') }}</span>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-primary btn-logout" type="submit">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
.meditech-navbar-paciente {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-bottom: 2px solid #3a7bd5;
}

.navbar-logo {
    width: 40px;
    height: 40px;
}

.brand-text {
    line-height: 1.2;
}

.brand-title {
    font-weight: 700;
    font-size: 1.25rem;
    color: #1a4b8c;
}

.brand-subtitle {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 400;
}

.navbar-nav .nav-link {
    color: #495057 !important;
    font-weight: 500;
    padding: 0.75rem 1rem !important;
    border-radius: 0.375rem;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #1a4b8c !important;
    background-color: rgba(58, 123, 213, 0.1);
    transform: translateY(-1px);
}

.navbar-nav .nav-link.active {
    color: #ffffff !important;
    background-color: #3a7bd5;
    box-shadow: 0 2px 8px rgba(58, 123, 213, 0.3);
}

.navbar-nav .nav-link i {
    color: #3a7bd5;
}

.navbar-nav .nav-link.active i {
    color: #ffffff;
}

.btn-logout {
    border: 2px solid #3a7bd5 !important;
    color: #3a7bd5 !important;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background-color: #3a7bd5 !important;
    border-color: #3a7bd5 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
}

.navbar-text {
    background: rgba(58, 123, 213, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    border: 1px solid rgba(58, 123, 213, 0.2);
}

.navbar-toggler {
    border-color: #3a7bd5;
}

.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2858, 123, 213, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}

@media (max-width: 991px) {
    .navbar-nav {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(58, 123, 213, 0.2);
    }
    
    .navbar-nav .nav-link {
        margin: 0.25rem 0;
    }
}
</style>
