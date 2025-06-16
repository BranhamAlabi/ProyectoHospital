@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #1a4b8c 0%, #12153b 100%);">
        <div class="card-header bg-transparent border-bottom border-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-white">
                    <i class="fas fa-hospital me-2"></i>Información de la Clínica
                </h2>
                @if(session('usuario_rol') === 'administrador')
                <a href="{{ route('clinica.edit') }}" class="btn btn-outline-light">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                @endif
            </div>
        </div>

        <div class="card-body text-light">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <div class="info-item mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-signature me-3 text-primary"></i>
                            <h5 class="mb-0">Nombre</h5>
                        </div>
                        <p class="mb-0 ps-4">{{ $clinica->nombre ?? 'No especificado' }}</p>
                    </div>

                    <div class="info-item mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marked-alt me-3 text-primary"></i>
                            <h5 class="mb-0">Dirección</h5>
                        </div>
                        <p class="mb-0 ps-4">{{ $clinica->direccion ?? 'No especificado' }}</p>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <div class="info-item mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone-alt me-3 text-primary"></i>
                            <h5 class="mb-0">Teléfono</h5>
                        </div>
                        <p class="mb-0 ps-4">{{ $clinica->telefono ?? 'No especificado' }}</p>
                    </div>

                    <div class="info-item mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            <h5 class="mb-0">Correo Electrónico</h5>
                        </div>
                        <p class="mb-0 ps-4">{{ $clinica->email ?? 'No especificado' }}</p>
                    </div>

                    <div class="info-item mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tie me-3 text-primary"></i>
                            <h5 class="mb-0">Responsable</h5>
                        </div>
                        <p class="mb-0 ps-4">{{ $clinica->responsable ?? 'No especificado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .info-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .info-item:hover {
        transform: translateY(-3px);
        border-left-color: #3a7bd5;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .text-primary {
        color: #3a7bd5 !important;
    }
    
    .bg-opacity-25 {
        background-color: rgba(0, 0, 0, 0.25);
    }
    
    @media (max-width: 768px) {
        .info-item {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection
