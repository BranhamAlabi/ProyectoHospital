<nav class="navbar navbar-expand-lg navbar-dark meditech-navbar-medico">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('medico.inicio') }}">
            <img src="{{ asset('image/meditech_logo.png') }}" alt="MediTech Logo" class="navbar-logo me-2">
            <div class="brand-text">
                <span class="brand-title">MediTech</span>
                <small class="brand-subtitle d-block">Panel Médico</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMedico" aria-controls="navbarMedico" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMedico">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.inicio') ? 'active' : '' }}" href="{{ route('medico.inicio') }}">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.expedientes*') ? 'active' : '' }}" href="{{ route('medico.expedientes') }}">
                        <i class="fas fa-folder-open me-1"></i>Expedientes
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.horarios') ? 'active' : '' }}" href="{{ route('medico.horarios') }}">
                        <i class="fas fa-clock me-1"></i>Horarios
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
                <i class="fas fa-user-md me-1"></i>
                <span class="text-light">Dr. {{ session('usuario_nombre', 'Usuario') }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-logout" type="submit">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
.meditech-navbar-medico {
    background: linear-gradient(135deg, #2e6136 0%, #1a4b2e 100%) !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    border-bottom: 2px solid #4caf50;
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
    background-color: rgba(76, 175, 80, 0.3);
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.navbar-nav .nav-link i {
    color: #4caf50;
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

<script>
// Función para cargar notificaciones
function cargarNotificaciones() {
    // Esta función se puede implementar para cargar notificaciones vía AJAX
    // Por ahora es un placeholder
}

// Cargar notificaciones al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    cargarNotificaciones();
});
</script>
