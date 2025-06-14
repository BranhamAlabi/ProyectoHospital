<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('gestion.inicioPaciente') }}">MediTech - Paciente</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPaciente" aria-controls="navbarPaciente" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarPaciente">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('gestion.inicioPaciente') }}">Inicio</a>
        </li>        <li class="nav-item">
          <a class="nav-link" href="{{ route('paciente.citas.index') }}">Mis Citas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('paciente.expedientes') }}">Mis expedientes</a>
        </li>        <li class="nav-item">
          <a class="nav-link" href="{{ route('paciente.expediente.personal') }}">Expediente Personal</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('perfil.show') }}">
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
