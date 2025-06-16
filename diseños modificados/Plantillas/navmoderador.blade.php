<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('gestion.inicio') }}">
            <i class="fas fa-user-shield me-2"></i>
            <span>Panel de Gestión</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModerador" aria-controls="navbarModerador" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModerador">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('gestion.inicio') }}">
                        <i class="fas fa-home me-1"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('usuarios.index') }}">
                        <i class="fas fa-users-cog me-1"></i>
                        <span>Gestión de Usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('clinica.show') }}">
                        <i class="fas fa-clinic-medical me-1"></i>
                        <span>Gestión de Clínicas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center active" href="{{ route('citas.index') }}">
                        <i class="fas fa-calendar-check me-1"></i>
                        <span>Gestión de Citas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('medicos.index') }}">
                        <i class="fas fa-user-md me-1"></i>
                        <span>Gestión de Médicos</span>
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    }
    .navbar {
        padding: 0.5rem 1rem;
    }
    .nav-link {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        font-weight: 500;
    }
    .navbar-brand {
        font-weight: 600;
    }
</style>