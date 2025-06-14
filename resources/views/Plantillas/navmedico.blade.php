<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <a class="nav-link {{ request()->routeIs('medico.expedientes*') ? 'active' : '' }}" href="{{ route('medico.expedientes') }}">
                        <i class="fas fa-folder-open"></i> Expedientes
                    </a>
                </li>                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medico.horarios') ? 'active' : '' }}" href="{{ route('medico.horarios') }}">
                        <i class="fas fa-clock"></i> Horarios
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}" href="{{ route('perfil.show') }}">
                        <i class="fas fa-user-circle"></i> Mi Perfil
                    </a>
                </li>
                
            </ul>
            
            

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger" type="submit">Cerrar Sesión</button>
            </form>
            
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
