@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-user-edit"></i> Expediente Personal - {{ $paciente->nombre }}
            </h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h6><i class="fas fa-exclamation-triangle"></i> Por favor, corrige los siguientes errores:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('medico.guardarExpedientePersonal', $paciente->id) }}" method="POST">
                @csrf
                  <div class="row">
                    <!-- Información Personal -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3 text-primary">
                            <i class="fas fa-user"></i> Información Personal de {{ $paciente->nombre }}
                        </h6>
                    </div>
                      <div class="col-md-4 mb-3">
                        <label for="fecha_nacimiento" class="form-label">
                            <i class="fas fa-calendar"></i> Fecha de Nacimiento *
                        </label>
                        
                        
                        <input type="date" 
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                               id="fecha_nacimiento" 
                               name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento', $expedientePersonal && $expedientePersonal->fecha_nacimiento ? \Carbon\Carbon::parse($expedientePersonal->fecha_nacimiento)->format('Y-m-d') : '') }}" 
                               required>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div><div class="col-md-4 mb-3">
                        <label for="sexo" class="form-label">
                            <i class="fas fa-venus-mars"></i> Sexo *
                        </label>
                        <select class="form-select @error('sexo') is-invalid @enderror" 
                                id="sexo" 
                                name="sexo" 
                                required>
                            <option value="">Seleccionar...</option>
                            <option value="M" {{ old('sexo', $expedientePersonal->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo', $expedientePersonal->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('sexo', $expedientePersonal->sexo ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('sexo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>                    <div class="col-md-4 mb-3">
                        <label for="telefono" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono *
                        </label>
                        <input type="tel" 
                               class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono', $expedientePersonal->telefono ?? '') }}" 
                               required>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="direccion" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Dirección *
                        </label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                  id="direccion" 
                                  name="direccion" 
                                  rows="2" 
                                  required>{{ old('direccion', $expedientePersonal->direccion ?? '') }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Información Médica -->
                    <div class="col-12 mt-4">
                        <h6 class="border-bottom pb-2 mb-3 text-primary">
                            <i class="fas fa-heartbeat"></i> Información Médica
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="enfermedades_cronicas" class="form-label">
                            <i class="fas fa-disease"></i> Enfermedades Crónicas
                        </label>
                        <textarea class="form-control @error('enfermedades_cronicas') is-invalid @enderror" 
                                  id="enfermedades_cronicas" 
                                  name="enfermedades_cronicas" 
                                  rows="4" 
                                  placeholder="Diabetes, hipertensión, asma, etc...">{{ old('enfermedades_cronicas', $expedientePersonal->enfermedades_cronicas ?? '') }}</textarea>
                        @error('enfermedades_cronicas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cirugias_previas" class="form-label">
                            <i class="fas fa-cut"></i> Cirugías Previas
                        </label>
                        <textarea class="form-control @error('cirugias_previas') is-invalid @enderror" 
                                  id="cirugias_previas" 
                                  name="cirugias_previas" 
                                  rows="4" 
                                  placeholder="Apendicectomía, colecistectomía, etc...">{{ old('cirugias_previas', $expedientePersonal->cirugias_previas ?? '') }}</textarea>
                        @error('cirugias_previas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="alergias" class="form-label">
                            <i class="fas fa-exclamation-triangle"></i> Alergias
                        </label>
                        <textarea class="form-control @error('alergias') is-invalid @enderror" 
                                  id="alergias" 
                                  name="alergias" 
                                  rows="4" 
                                  placeholder="Penicilina, mariscos, pólenes, etc...">{{ old('alergias', $expedientePersonal->alergias ?? '') }}</textarea>
                        @error('alergias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tratamientos_actuales" class="form-label">
                            <i class="fas fa-pills"></i> Tratamientos Actuales
                        </label>
                        <textarea class="form-control @error('tratamientos_actuales') is-invalid @enderror" 
                                  id="tratamientos_actuales" 
                                  name="tratamientos_actuales" 
                                  rows="4" 
                                  placeholder="Medicamentos, terapias, tratamientos en curso...">{{ old('tratamientos_actuales', $expedientePersonal->tratamientos_actuales ?? '') }}</textarea>
                        @error('tratamientos_actuales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Información del registro -->
                @if($expedientePersonal && $expedientePersonal->editado_por)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Última actualización:</strong> 
                                    {{ $expedientePersonal->updated_at->format('d/m/Y H:i') }}
                                    @if($expedientePersonal->medico)
                                        por Dr. {{ $expedientePersonal->medico->usuario->nombre }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Expediente Personal
                        </button>
                        <a href="{{ route('medico.expedientes') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Regresar a Expedientes
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.form-label i {
    color: #007bff;
    margin-right: 5px;
}

.border-bottom {
    border-color: #dee2e6 !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.alert {
    border-radius: 8px;
}

textarea.form-control {
    resize: vertical;
    min-height: 60px;
}
</style>
@endpush
