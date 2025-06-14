<![CDATA[<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('gestion.inicio') }}">Panel de Gestión</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGestion" aria-controls="navbarGestion" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarGestion">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('gestion.inicio') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('usuarios.index') }}">Gestión de Usuarios</a>
                </li>
                @if(in_array('administrador', session('usuario_rol', [])))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clinica.index') }}">Gestión de Clínicas</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('citas.index') }}">Gestión de Citas</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>]]>
