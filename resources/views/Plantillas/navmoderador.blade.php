<nav class="navbar navbar-expand-lg navbar-dark meditech-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('gestion.inicio') }}">
            <img src="{{ asset('image/meditech_logo.png') }}" alt="MediTech Logo" class="navbar-logo me-2">
            <div class="brand-text">
                <span class="brand-title">MediTech</span>
                <small class="brand-subtitle d-block">Panel de Gestión</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModerador" aria-controls="navbarModerador" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModerador">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gestion.inicio') ? 'active' : '' }}" href="{{ route('gestion.inicio') }}">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                        <i class="fas fa-users me-1"></i>Gestión de Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clinica.*') ? 'active' : '' }}" href="{{ route('clinica.index') }}">
                        <i class="fas fa-hospital me-1"></i>Gestión de Clínicas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}" href="{{ route('citas.index') }}">
                        <i class="fas fa-calendar-alt me-1"></i>Gestión de Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}" href="{{ route('medicos.index') }}">
                        <i class="fas fa-user-md me-1"></i>Gestión de Médicos
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
                <i class="fas fa-user-shield me-1"></i>
                <span class="text-light">{{ session('usuario_nombre', 'Usuario') }}</span>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
.meditech-navbar {
    background: linear-gradient(135deg, #1a4b8c 0%, #12153b 100%) !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    border-bottom: 2px solid #3a7bd5;
}

.navbar-logo {
    width: 40px;
    height: 40px;
    filter: brightness(1.1);
}

.brand-text {
    line-height: 1.2;
}

.brand-title {
    font-weight: 700;
    font-size: 1.25rem;
    color: #ffffff;
}

.brand-subtitle {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 400;
}

.navbar-nav .nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500;
    padding: 0.75rem 1rem !important;
    border-radius: 0.375rem;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #ffffff !important;
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

.navbar-nav .nav-link.active {
    color: #ffffff !important;
    background-color: rgba(58, 123, 213, 0.3);
    box-shadow: 0 2px 8px rgba(58, 123, 213, 0.3);
}

.navbar-nav .nav-link i {
    color: #3a7bd5;
}

.btn-logout {
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background-color: rgba(239, 68, 68, 0.2) !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
}

.navbar-text {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

@media (max-width: 991px) {
    .navbar-nav {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .navbar-nav .nav-link {
        margin: 0.25rem 0;
    }
}
</style>
