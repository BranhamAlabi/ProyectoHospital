@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navgestion')

<div class="container mt-4">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-hospital me-2"></i>Editar Información de la Clínica
                </h2>
                <a href="{{ route('clinica.show') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error en el formulario:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('clinica.update', $clinica->id) }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" id="nombre" name="nombre" class="form-control shadow-sm" 
                                   value="{{ old('nombre', $clinica->nombre ?? '') }}" required>
                            <label for="nombre">Nombre</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="text" id="responsable" name="responsable" class="form-control shadow-sm" 
                                   value="{{ old('responsable', $clinica->responsable ?? '') }}" required>
                            <label for="responsable">Responsable</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-floating">
                        <input type="text" id="direccion" name="direccion" class="form-control shadow-sm" 
                               value="{{ old('direccion', $clinica->direccion ?? '') }}" required>
                        <label for="direccion">Dirección</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="tel" id="telefono" name="telefono" class="form-control shadow-sm" 
                                   value="{{ old('telefono', $clinica->telefono ?? '') }}" required>
                            <label for="telefono">Teléfono</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="form-floating">
                            <input type="email" id="email" name="email" class="form-control shadow-sm" 
                                   value="{{ old('email', $clinica->email ?? '') }}" required>
                            <label for="email">Correo Electrónico</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-3">
                    <a href="{{ route('clinica.show') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
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
        padding: 1rem 1.5rem;
    }
    
    .form-floating {
        position: relative;
    }
    
    .form-floating label {
        color: #6c757d;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }
    
    .btn-outline-secondary {
        border-width: 2px;
    }
    
    .alert {
        border-radius: 0.5rem;
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
