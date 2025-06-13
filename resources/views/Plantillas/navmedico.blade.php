<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('medico.inicio') }}">
            <i class="fas fa-user-md"></i> Panel Médico
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMedico" aria-controls="navbarMedico" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMedico">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.inicio') ? 'active' : '' }}" href="{{ route('medico.inicio') }}">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}" href="{{ route('citas.index') }}">
                        <i class="fas fa-calendar-alt"></i> Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.expedientes*') ? 'active' : '' }}" href="{{ route('medico.expedientes') }}">
                        <i class="fas fa-folder-open"></i> Expedientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.horarios') ? 'active' : '' }}" href="{{ route('medico.horarios') }}">
                        <i class="fas fa-clock"></i> Horarios
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('medico.horarios') }}">
                            <i class="fas fa-clock"></i> Horarios de Atención
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-bell"></i> Notificaciones
                        </a></li>
                    </ul>
                </li>
            </ul>
            
            <!-- Notificaciones -->
            <div class="navbar-nav me-3">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                            0
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 300px;">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li id="notificationsList">
                            <div class="dropdown-item-text text-muted text-center">
                                No hay notificaciones nuevas
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Usuario -->
            <div class="navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> {{ session('usuario_nombre') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-user"></i> Mi Perfil
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

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
