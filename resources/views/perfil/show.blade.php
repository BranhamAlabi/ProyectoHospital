@extends('Plantillas.sesion')

@section('Contenido')
@if(Auth::user()->roles->contains('nombre', 'paciente'))
    @include('Plantillas.navpaciente')
@elseif(Auth::user()->roles->contains('nombre', 'medico'))
    @include('Plantillas.navmedico')
@else
    @include('Plantillas.navmoderador')
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-user-circle me-2"></i>Mi Perfil
    </h1>    <ol class="breadcrumb mb-4">
        @if(Auth::user()->roles->contains('nombre', 'Paciente'))
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicioPaciente') }}">Dashboard</a></li>
        @elseif(Auth::user()->roles->contains('nombre', 'Medico'))
            <li class="breadcrumb-item"><a href="{{ route('medico.inicio') }}">Dashboard</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicio') }}">Dashboard</a></li>
        @endif
        <li class="breadcrumb-item active">Mi Perfil</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información Personal -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre Completo:</strong><br>
                            <span class="text-muted">{{ $usuario->nombre }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Correo Electrónico:</strong><br>
                            <span class="text-muted">{{ $usuario->correo }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Estado de la Cuenta:</strong><br>
                            <span class="badge bg-{{ $usuario->estado == 'activo' ? 'success' : 'danger' }}">
                                {{ ucfirst($usuario->estado) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de Registro:</strong><br>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Roles en el Sistema:</strong><br>
                            @foreach($usuario->roles as $rol)
                                <span class="badge bg-info me-2">{{ $rol->nombre }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('perfil.edit') }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Editar Información
                        </a>
                        <a href="{{ route('perfil.change-password') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-lock me-1"></i> Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Información del Rol -->
            @if($usuario->roles->contains('nombre', 'Medico') && isset($datosAdicionales['medico']))
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>Información Médica
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Especialidades:</strong><br>
                            @if($datosAdicionales['especialidades'] && $datosAdicionales['especialidades']->count() > 0)
                                @foreach($datosAdicionales['especialidades'] as $especialidad)
                                    <span class="badge bg-success me-1 mb-1">{{ $especialidad->especialidad }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No asignadas</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Clínicas:</strong><br>
                            @if($datosAdicionales['clinicas'] && $datosAdicionales['clinicas']->count() > 0)
                                @foreach($datosAdicionales['clinicas'] as $clinica)
                                    <span class="badge bg-info me-1 mb-1">{{ $clinica->nombre }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No asignadas</span>
                            @endif
                        </div>
                        <div class="mb-0">
                            <strong>Código de Médico:</strong><br>
                            <span class="text-muted">#{{ str_pad($datosAdicionales['medico']->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($usuario->roles->contains('nombre', 'Paciente') && isset($datosAdicionales['estadisticas_citas']))
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Estadísticas de Citas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-light p-2 rounded">
                                    <h5 class="mb-1 text-primary">{{ $datosAdicionales['estadisticas_citas']['total'] }}</h5>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-light p-2 rounded">
                                    <h5 class="mb-1 text-success">{{ $datosAdicionales['estadisticas_citas']['aprobadas'] }}</h5>
                                    <small class="text-muted">Aprobadas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <h5 class="mb-1 text-warning">{{ $datosAdicionales['estadisticas_citas']['pendientes'] }}</h5>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <h5 class="mb-1 text-danger">{{ $datosAdicionales['estadisticas_citas']['canceladas'] }}</h5>
                                    <small class="text-muted">Canceladas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Información de Seguridad -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Seguridad
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-lock text-muted me-2"></i>
                        <small>Contraseña protegida</small>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        <small>Correo verificado</small>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Mantén tu información actualizada para la seguridad de tu cuenta.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border: none;
    }

    .card-header {
        border-bottom: none;
        font-weight: 600;
    }

    .badge {
        font-size: 0.85em;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .btn {
        border-radius: 8px;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }
</style>
@endsection
