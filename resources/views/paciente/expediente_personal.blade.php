@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-user-md me-2"></i>Mi Expediente Personal
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('paciente.inicio') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Expediente Personal</li>
    </ol>

    @if($expedientePersonal)
        <div class="row">
            <!-- Información Personal -->
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user me-1"></i>
                        Información Personal
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Nombre Completo:</strong></label>
                                    <p class="form-control-plaintext">{{ $expedientePersonal->nombre_completo }}</p>
                                </div>                                <div class="mb-3">
                                    <label class="form-label"><strong>Fecha de Nacimiento:</strong></label>
                                    <p class="form-control-plaintext">
                                        @if($expedientePersonal->fecha_nacimiento)
                                            {{ $expedientePersonal->fecha_nacimiento->format('d/m/Y') }}
                                            <small class="text-muted">({{ $expedientePersonal->edad }} años)</small>
                                        @else
                                            <span class="text-muted">No registrada</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Sexo:</strong></label>
                                    <p class="form-control-plaintext">{{ $expedientePersonal->sexo_formateado }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Teléfono:</strong></label>
                                    <p class="form-control-plaintext">{{ $expedientePersonal->telefono }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Dirección:</strong></label>
                                    <p class="form-control-plaintext">{{ $expedientePersonal->direccion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Médica -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-heartbeat me-1"></i>
                        Información Médica
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Enfermedades Crónicas:</strong></label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $expedientePersonal->enfermedades_cronicas ?: 'No registradas' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Cirugías Previas:</strong></label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $expedientePersonal->cirugias_previas ?: 'No registradas' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Alergias:</strong></label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $expedientePersonal->alergias ?: 'No registradas' }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Tratamientos Actuales:</strong></label>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $expedientePersonal->tratamientos_actuales ?: 'No registrados' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i>
                        Información del Expediente
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-file-medical fa-3x text-primary"></i>
                        </div>
                        
                        @if($expedientePersonal->medico)
                            <div class="mb-3">
                                <label class="form-label"><strong>Última actualización por:</strong></label>
                                <p class="form-control-plaintext">
                                    Dr. {{ $expedientePersonal->medico->usuario->nombre ?? 'No disponible' }}
                                </p>
                            </div>
                        @endif                        <div class="mb-3">
                            <label class="form-label"><strong>Fecha de última actualización:</strong></label>
                            <p class="form-control-plaintext">
                                @if($expedientePersonal->updated_at)
                                    {{ $expedientePersonal->updated_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Expediente creado:</strong></label>
                            <p class="form-control-plaintext">
                                @if($expedientePersonal->created_at)
                                    {{ $expedientePersonal->created_at->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tools me-1"></i>
                        Acciones
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('paciente.inicio') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver al Dashboard
                            </a>
                            <a href="{{ route('paciente.expedientes') }}" class="btn btn-info">
                                <i class="fas fa-notes-medical me-1"></i>
                                Ver Notas Médicas
                            </a>
                            <a href="{{ route('paciente.citas.index') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Ver Mis Citas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No hay expediente -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-medical fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No tienes expediente personal registrado</h4>
                        <p class="text-muted">
                            Tu expediente personal será creado por un médico durante tu primera consulta.
                            Este expediente contendrá tu información médica personal, historial de enfermedades,
                            alergias y tratamientos.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Agendar Primera Cita
                            </a>
                            <a href="{{ route('paciente.inicio') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Animación suave para los elementos
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection
