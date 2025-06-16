@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tachometer-alt fa-2x me-3"></i>
                        <h2 class="mb-0">Panel de Gestión MediTech</h2>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <img src="{{ asset('images/meditech-logo.png') }}" alt="MediTech Logo" class="img-fluid mb-4" style="max-height: 120px;">
                        <h3 class="fw-bold text-primary">¡Bienvenido, {{ Auth::user()->nombre }}!</h3>
                        <p class="lead text-muted">Seleccione una opción en el menú superior para comenzar</p>
                    </div>

                    <div class="row g-4">
                        <!-- Tarjeta Usuarios -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                                        <i class="fas fa-users fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Gestión de Usuarios</h5>
                                    <p class="card-text text-muted">Administre los usuarios del sistema</p>
                                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary stretched-link">
                                        Acceder <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta Clínicas -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                                        <i class="fas fa-hospital fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Gestión de Clínicas</h5>
                                    <p class="card-text text-muted">Administre las clínicas disponibles</p>
                                    <a href="{{ route('clinica.show') }}" class="btn btn-outline-primary stretched-link">
                                        Acceder <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta Citas -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block mb-3">
                                        <i class="fas fa-calendar-check fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Gestión de Citas</h5>
                                    <p class="card-text text-muted">Administre el calendario de citas</p>
                                    <a href="{{ route('citas.index') }}" class="btn btn-outline-primary stretched-link">
                                        Acceder <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary {
        border-width: 2px;
    }
    
    .bg-opacity-10 {
        background-color: rgba(26, 75, 140, 0.1);
    }
</style>
@endsection