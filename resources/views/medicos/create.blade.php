@extends('Plantillas.sesion')

@section('Contenido')
@if(in_array(session('usuario_rol'), ['administrador', 'moderador']))
    @include('Plantillas.navmoderador')
@endif

<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>Agregar Médico</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error en el formulario</h5>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('medicos.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- Campo Clínicas -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="clinica_ids" class="form-label fw-bold mb-2">
                                <i class="fas fa-hospital me-1"></i>Clínicas
                            </label>
                            <select name="clinica_ids[]" id="clinica_ids" class="form-select shadow-sm" multiple required style="height: 150px;">
                                @foreach($clinicas as $clinica)
                                    <option value="{{ $clinica->id }}" @selected(in_array($clinica->id, old('clinica_ids', [])))>
                                        {{ $clinica->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-info-circle me-1"></i>Mantenga presionada Ctrl (Cmd en Mac) para seleccionar múltiples clínicas.
                            </small>
                        </div>
                    </div>

                    <!-- Campo Médico -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="usuario_id" class="form-label fw-bold mb-2">
                                <i class="fas fa-user-md me-1"></i>Seleccionar Médico
                            </label>
                            <select name="usuario_id" id="usuario_id" class="form-select shadow-sm" required>
                                <option value="">Seleccione un médico...</option>
                                @foreach($usuariosMedicos as $usuario)
                                    <option value="{{ $usuario->id }}" @selected(old('usuario_id') == $usuario->id)>
                                        {{ $usuario->nombre }} ({{ $usuario->correo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Campo Especialidades -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="especialidad_ids" class="form-label fw-bold mb-2">
                                <i class="fas fa-stethoscope me-1"></i>Especialidades
                            </label>
                            <select name="especialidad_ids[]" id="especialidad_ids" class="form-select shadow-sm" multiple required style="height: 150px;">
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad->id }}" @selected(in_array($especialidad->id, old('especialidad_ids', [])))>
                                        {{ $especialidad->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-info-circle me-1"></i>Mantenga presionada Ctrl (Cmd en Mac) para seleccionar múltiples especialidades.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('medicos.index') }}" class="btn btn-outline-secondary shadow-sm px-4">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm px-4">
                        <i class="fas fa-save me-1"></i> Agregar Médico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        border: none;
    }
    
    .card-header {
        padding: 1rem 1.5rem;
    }
    
    .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        border: 1px solid #ced4da;
        transition: all 0.2s;
    }
    
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: #1a4b8c;
        border-color: #1a4b8c;
    }
    
    .btn-primary:hover {
        background-color: #123366;
        border-color: #102a57;
    }
    
    .btn-outline-secondary {
        border-width: 2px;
    }
    
    .alert {
        border-left: 4px solid #dc3545;
    }
</style>

<script>
    // Validación de formulario
    (function() {
        'use strict'
        
        const forms = document.querySelectorAll('.needs-validation')
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
