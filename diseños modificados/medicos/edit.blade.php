@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-user-md me-2"></i>Editar Médico</h2>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('medicos.update', $medico->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" name="nombre" id="nombre" class="form-control shadow-sm" 
                                   value="{{ old('nombre', $medico->usuario->nombre) }}" required>
                            <label for="nombre" class="form-label">Nombre</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="email" name="correo" id="correo" class="form-control shadow-sm" 
                                   value="{{ old('correo', $medico->usuario->correo) }}" required>
                            <label for="correo" class="form-label">Correo</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="clinica_ids" class="form-label fw-bold mb-2"><i class="fas fa-hospital me-1"></i>Clínicas</label>
                    <select name="clinica_ids[]" id="clinica_ids" class="form-select shadow-sm" multiple required style="height: 150px;">
                        @foreach($clinicas as $clinica)
                            <option value="{{ $clinica->id }}" 
                                @selected(in_array($clinica->id, old('clinica_ids', $medico->clinicas->pluck('id')->toArray())))>
                                {{ $clinica->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted mt-1">
                        <i class="fas fa-info-circle me-1"></i>Mantenga presionada Ctrl (Cmd en Mac) para seleccionar múltiples clínicas.
                    </small>
                </div>

                <div class="mb-4">
                    <label for="especialidad_ids" class="form-label fw-bold mb-2"><i class="fas fa-stethoscope me-1"></i>Especialidades</label>
                    <select name="especialidad_ids[]" id="especialidad_ids" class="form-select shadow-sm" multiple required style="height: 150px;">
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}" 
                                @selected(in_array($especialidad->id, old('especialidad_ids', $medico->especialidades->pluck('id')->toArray())))>
                                {{ $especialidad->especialidad }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted mt-1">
                        <i class="fas fa-info-circle me-1"></i>Mantenga presionada Ctrl (Cmd en Mac) para seleccionar múltiples especialidades.
                    </small>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('medicos.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
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
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .form-floating {
        position: relative;
    }
    
    .form-floating label {
        color: #6c757d;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: #1a4b8c;
        border-color: #1a4b8c;
    }
    
    .btn-primary:hover {
        background-color: #123366;
        border-color: #123366;
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