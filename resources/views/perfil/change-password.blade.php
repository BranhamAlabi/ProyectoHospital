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
        <i class="fas fa-lock me-2"></i>Cambiar Contraseña
    </h1>    <ol class="breadcrumb mb-4">
        @if(Auth::user()->roles->contains('nombre', 'Paciente'))
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicioPaciente') }}">Dashboard</a></li>
        @elseif(Auth::user()->roles->contains('nombre', 'Medico'))
            <li class="breadcrumb-item"><a href="{{ route('medico.inicio') }}">Dashboard</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('gestion.inicio') }}">Dashboard</a></li>
        @endif
        <li class="breadcrumb-item"><a href="{{ route('perfil.show') }}">Mi Perfil</a></li>
        <li class="breadcrumb-item active">Cambiar Contraseña</li>
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
    @endif    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Actualizar Contraseña
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Tu nueva contraseña debe tener al menos 8 caracteres y ser segura.
                    </div>

                    <form action="{{ route('perfil.change-password.update') }}" method="POST" id="formCambiarContrasena">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual *</label>
                            <div class="input-group">
                                <input type="password" name="contrasena_actual" id="contrasena_actual" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('contrasena_actual')">
                                    <i class="fas fa-eye" id="icon_contrasena_actual"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Ingresa tu contraseña actual para confirmar el cambio</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña *</label>
                            <div class="input-group">
                                <input type="password" name="contrasena_nueva" id="contrasena_nueva" class="form-control" 
                                       minlength="8" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('contrasena_nueva')">
                                    <i class="fas fa-eye" id="icon_contrasena_nueva"></i>
                                </button>
                            </div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" id="passwordStrength" style="width: 0%"></div>
                            </div>
                            <small class="form-text" id="passwordStrengthText">Mínimo 8 caracteres</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirmar Nueva Contraseña *</label>
                            <div class="input-group">
                                <input type="password" name="contrasena_nueva_confirmation" id="contrasena_nueva_confirmation" 
                                       class="form-control" minlength="8" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('contrasena_nueva_confirmation')">
                                    <i class="fas fa-eye" id="icon_contrasena_nueva_confirmation"></i>
                                </button>
                            </div>
                            <small class="form-text" id="confirmationText">Repite la nueva contraseña</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perfil.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger" id="btnSubmit" disabled>
                                <i class="fas fa-key me-1"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de ayuda -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Consejos de Seguridad
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Una contraseña segura debe tener:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Al menos 8 caracteres de longitud
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Una combinación de letras mayúsculas y minúsculas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Al menos un número
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Al menos un carácter especial (@, #, $, %, etc.)
                        </li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Recomendaciones:</h6>
                    <ul class="small text-muted">
                        <li>No uses información personal obvia</li>
                        <li>No compartas tu contraseña</li>
                        <li>Cambia tu contraseña regularmente</li>
                        <li>Usa contraseñas únicas para cada servicio</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Función para esperar a que el DOM esté completamente cargado
function inicializarCambioContrasena() {
    console.log('🚀 Iniciando validación de cambio de contraseña...');
    
    const contrasenaActual = document.getElementById('contrasena_actual');
    const contrasenaNueva = document.getElementById('contrasena_nueva');
    const contrasenaConfirmacion = document.getElementById('contrasena_nueva_confirmation');
    const btnSubmit = document.getElementById('btnSubmit');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    const confirmationText = document.getElementById('confirmationText');

    // Verificar que todos los elementos estén presentes
    console.log('🔍 Elementos encontrados:', {
        contrasenaActual: !!contrasenaActual,
        contrasenaNueva: !!contrasenaNueva,
        contrasenaConfirmacion: !!contrasenaConfirmacion,
        btnSubmit: !!btnSubmit,
        passwordStrength: !!passwordStrength,
        passwordStrengthText: !!passwordStrengthText,
        confirmationText: !!confirmationText
    });

    if (!contrasenaActual || !contrasenaNueva || !contrasenaConfirmacion || !btnSubmit) {
        console.error('❌ Algunos elementos críticos no se encontraron. Reintentando en 1 segundo...');
        setTimeout(inicializarCambioContrasena, 1000);
        return;
    }

    console.log('✅ Todos los elementos encontrados. Configurando event listeners...');

    // Verificar fortaleza de la contraseña
    contrasenaNueva.addEventListener('input', function() {
        console.log('Nueva contraseña cambiada, longitud:', this.value.length);
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        // Actualizar barra de progreso si existe
        if (passwordStrength && passwordStrengthText) {
            passwordStrength.style.width = strength.percentage + '%';
            passwordStrength.className = 'progress-bar ' + strength.class;
            passwordStrengthText.textContent = strength.text;
            passwordStrengthText.className = 'form-text ' + strength.textClass;
        }
        
        validarFormulario();
    });

    // Verificar que las contraseñas coincidan
    contrasenaConfirmacion.addEventListener('input', function() {
        console.log('Confirmación de contraseña cambiada');
        const nueva = contrasenaNueva.value;
        const confirmacion = this.value;
        
        if (confirmationText) {
            if (confirmacion === '') {
                confirmationText.textContent = 'Repite la nueva contraseña';
                confirmationText.className = 'form-text text-muted';
            } else if (nueva === confirmacion) {
                confirmationText.textContent = '✓ Las contraseñas coinciden';
                confirmationText.className = 'form-text text-success';
            } else {
                confirmationText.textContent = '✗ Las contraseñas no coinciden';
                confirmationText.className = 'form-text text-danger';
            }
        }
        
        validarFormulario();
    });

    contrasenaActual.addEventListener('input', function() {
        console.log('Contraseña actual cambiada, longitud:', this.value.length);
        validarFormulario();
    });

    function checkPasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (score < 2) {
            return {
                percentage: 20,
                class: 'bg-danger',
                text: 'Muy débil',
                textClass: 'text-danger'
            };
        } else if (score < 3) {
            return {
                percentage: 40,
                class: 'bg-warning',
                text: 'Débil',
                textClass: 'text-warning'
            };
        } else if (score < 4) {
            return {
                percentage: 60,
                class: 'bg-info',
                text: 'Moderada',
                textClass: 'text-info'
            };
        } else if (score < 5) {
            return {
                percentage: 80,
                class: 'bg-success',
                text: 'Fuerte',
                textClass: 'text-success'
            };
        } else {
            return {
                percentage: 100,
                class: 'bg-success',
                text: 'Muy fuerte',
                textClass: 'text-success'
            };        }
    }    function validarFormulario() {
        const actual = contrasenaActual.value.trim();
        const nueva = contrasenaNueva.value.trim();
        const confirmacion = contrasenaConfirmacion.value.trim();
        
        // Depuración detallada
        console.log('=== VALIDACIÓN DEL FORMULARIO ===');
        console.log('Contraseña actual:', actual.length > 0 ? 'Introducida' : 'Vacía');
        console.log('Nueva contraseña longitud:', nueva.length);
        console.log('Confirmación longitud:', confirmacion.length);
        console.log('¿Coinciden?:', nueva === confirmacion);
        console.log('¿Son diferentes?:', nueva !== actual);
        
        const actualValida = actual.length > 0;
        const nuevaValida = nueva.length >= 8;
        const coinciden = nueva === confirmacion && confirmacion.length > 0;
        const sonDiferentes = nueva !== actual;
        
        const esValido = actualValida && nuevaValida && coinciden && sonDiferentes;
        
        console.log('Validaciones individuales:', {
            actualValida,
            nuevaValida,
            coinciden,
            sonDiferentes,
            esValido
        });
        
        btnSubmit.disabled = !esValido;
        
        if (esValido) {
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-danger');
            console.log('✅ Formulario VÁLIDO - Botón habilitado');
        } else {
            btnSubmit.classList.remove('btn-danger');
            btnSubmit.classList.add('btn-secondary');
            console.log('❌ Formulario INVÁLIDO - Botón deshabilitado');
        }
    }    // Validación inicial
    validarFormulario();
    console.log('✅ Sistema de validación inicializado correctamente');
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarCambioContrasena);
} else {
    inicializarCambioContrasena();
}

function togglePassword(inputId) {
    console.log('Toggle password para:', inputId);
    const input = document.getElementById(inputId);
    const icon = document.getElementById('icon_' + inputId);
    
    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    } else {
        console.error('No se encontró el input o icono para:', inputId);
    }
}
</script>
@endpush

@push('styles')
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

    .input-group .btn {
        border-radius: 0 8px 8px 0;
    }

    .form-control {
        border-radius: 8px 0 0 8px;
    }

    .form-control:only-child {
        border-radius: 8px;
    }

    .progress {
        border-radius: 3px;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .btn {
        border-radius: 8px;    }
</style>
@endpush
