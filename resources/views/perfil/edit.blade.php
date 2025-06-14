@extends('Plantillas.sesion')

@section('Contenido')
@if(Auth::user()->roles->contains('nombre', 'Paciente'))
    @include('Plantillas.navpaciente')
@elseif(Auth::user()->roles->contains('nombre', 'Medico'))
    @include('Plantillas.navmedico')
@else
    @include('Plantillas.navmoderador')
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-user-edit me-2"></i>Editar Mi Perfil
    </h1>    <ol class="breadcrumb mb-4">
        @if(Auth::user()->roles->contains('nombre', 'Paciente'))
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicioPaciente') }}">Dashboard</a></li>
        @elseif(Auth::user()->roles->contains('nombre', 'Medico'))
            <li class="breadcrumb-item"><a href="{{ route('medico.inicio') }}">Dashboard</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicio') }}">Dashboard</a></li>
        @endif
        <li class="breadcrumb-item"><a href="{{ route('perfil.show') }}">Mi Perfil</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Actualizar Información Personal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información:</strong> Solo puedes modificar tu nombre y correo electrónico. Para cambios en roles o especialidades, contacta al administrador.
                    </div>

                    <form action="{{ route('perfil.update') }}" method="POST" id="formEditarPerfil">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" 
                                       value="{{ old('nombre', $usuario->nombre) }}" 
                                       maxlength="150" required>
                                <div class="form-text">
                                    <span id="nombreCount">{{ strlen($usuario->nombre) }}</span>/150 caracteres
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico *</label>
                                <input type="email" name="correo" id="correo" class="form-control" 
                                       value="{{ old('correo', $usuario->correo) }}" 
                                       maxlength="150" required>
                                <div class="form-text">
                                    <span id="correoCount">{{ strlen($usuario->correo) }}</span>/150 caracteres
                                </div>
                            </div>
                        </div>

                        <!-- Información no editable -->
                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="text-muted mb-3">Información del Sistema (No editable)</h6>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <strong>Estado:</strong><br>
                                    <span class="badge bg-{{ $usuario->estado == 'activo' ? 'success' : 'danger' }}">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Fecha de Registro:</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y') }}</small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Roles:</strong><br>
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge bg-info me-1">{{ $rol->nombre }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perfil.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning" id="btnSubmit">
                                <i class="fas fa-save me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const correoInput = document.getElementById('correo');
    const btnSubmit = document.getElementById('btnSubmit');

    // Contador de caracteres para nombre
    nombreInput.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('nombreCount').textContent = count;
        
        if (count > 140) {
            document.getElementById('nombreCount').classList.add('text-warning');
        } else {
            document.getElementById('nombreCount').classList.remove('text-warning');
        }
        
        validarFormulario();
    });

    // Contador de caracteres para correo
    correoInput.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('correoCount').textContent = count;
        
        if (count > 140) {
            document.getElementById('correoCount').classList.add('text-warning');
        } else {
            document.getElementById('correoCount').classList.remove('text-warning');
        }
        
        validarFormulario();
    });

    // Validación del formulario
    function validarFormulario() {
        const nombre = nombreInput.value.trim();
        const correo = correoInput.value.trim();
        
        // Validar que los campos no estén vacíos
        if (nombre.length >= 2 && correo.length >= 5 && correo.includes('@')) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-warning');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.remove('btn-warning');
            btnSubmit.classList.add('btn-secondary');
        }
    }

    // Validación inicial
    validarFormulario();
});
</script>
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

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .badge {
        font-size: 0.85em;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .btn {
        border-radius: 8px;
    }

    .form-control {
        border-radius: 8px;
    }
</style>
@endsection
