<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ClinicaController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\CitaController;

Route::get('/', function () {
    return view('welcome');
});

// Login / Logout
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Registro de Paciente
Route::get('registrar', [AuthController::class, 'showRegister'])->name('register');
Route::post('registrar', [AuthController::class, 'register'])->name('register.post');

// Mostrar formulario de “Olvidé mi contraseña”
Route::get('recuperar-contrasena', [AuthController::class, 'showRecuperarContrasena'])
     ->name('password.request');

// Procesar el envío del correo de recuperación
Route::post('recuperar-contrasena', [AuthController::class, 'enviarEnlaceRecuperacion'])
     ->name('password.email');

// Mostrar formulario de restablecer usando el token
Route::get('resetear-contrasena/{token}', [AuthController::class, 'showResetForm'])
     ->name('password.reset');

// Procesar el envío de la nueva contraseña
Route::post('resetear-contrasena', [AuthController::class, 'resetPassword'])
     ->name('password.update');

use App\Http\Controllers\MedicosController;
use App\Http\Controllers\PacienteCitasController;

// Usuarios
Route::resource('usuarios', UsuariosController::class);

// Gestión inicio
Route::middleware('auth')->group(function () {
    Route::get('/gestion/inicio', [GestionController::class, 'inicio'])->name('gestion.inicio');
    Route::get('/gestion/inicio-paciente', [GestionController::class, 'inicioPaciente'])->name('gestion.inicioPaciente');

    // Clínica
    Route::get('/clinica', [ClinicaController::class, 'show'])->name('clinica.show');
    Route::get('/clinica/index', [ClinicaController::class, 'index'])->name('clinica.index');
    Route::get('/gestion/clinicas', [GestionController::class, 'listarClinicas'])->name('gestion.listarClinicas');

    // Expediente Médico Paciente
    Route::get('/paciente/expediente', [GestionController::class, 'expedientePaciente'])->name('paciente.expediente');
    Route::get('/paciente/expediente/pdf', [GestionController::class, 'expedientePacientePdf'])->name('paciente.expediente.pdf');

    // Notificaciones Paciente
    Route::get('/paciente/notificaciones', [GestionController::class, 'notificacionesPaciente'])->name('paciente.notificaciones');

    // Subir Documento Médico
    Route::post('/paciente/documento/subir', [GestionController::class, 'subirDocumentoPaciente'])->name('paciente.subirDocumento');

    // Actualizar Perfil Paciente
    Route::post('/paciente/perfil/actualizar', [GestionController::class, 'actualizarPerfilPaciente'])->name('paciente.actualizarPerfil');

    // Cambiar Contraseña Paciente
    Route::post('/paciente/contrasena/cambiar', [GestionController::class, 'cambiarContrasenaPaciente'])->name('paciente.cambiarContrasena');
    Route::get('/clinica/editar', [ClinicaController::class, 'edit'])->name('clinica.edit');
    Route::put('/clinica', [ClinicaController::class, 'update'])->name('clinica.update');

    // Citas de Pacientes
    Route::get('/paciente/citas/crear', [PacienteCitasController::class, 'create'])->name('paciente.citas.create');
    Route::post('/paciente/citas', [PacienteCitasController::class, 'store'])->name('paciente.citas.store');
    Route::get('/paciente/citas/medicos-por-especialidad', [PacienteCitasController::class, 'getMedicosPorEspecialidad'])->name('paciente.citas.medicosPorEspecialidad');
    Route::get('/paciente/citas/horarios-disponibles', [PacienteCitasController::class, 'getHorariosDisponibles'])->name('paciente.citas.horariosDisponibles');
    Route::post('/paciente/citas/{id}/cancelar', [PacienteCitasController::class, 'cancelar'])->name('paciente.citas.cancelar');

    // Cambiar nombre de ruta para inicio paciente
    Route::get('/paciente/inicio', [GestionController::class, 'inicioPaciente'])->name('paciente.inicio');

    // Citas
    Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    Route::get('/citas/{id}', [CitaController::class, 'show'])->name('citas.show');
    Route::post('/citas/{id}/update-status', [CitaController::class, 'updateStatus'])->name('citas.updateStatus');

    // Médicos
    Route::get('/medicos', [MedicosController::class, 'index'])->name('medicos.index');
    Route::get('/medicos/create', [MedicosController::class, 'create'])->name('medicos.create');
    Route::post('/medicos', [MedicosController::class, 'store'])->name('medicos.store');
    Route::get('/medicos/{id}/edit', [MedicosController::class, 'edit'])->name('medicos.edit');
    Route::put('/medicos/{id}', [MedicosController::class, 'update'])->name('medicos.update');
    Route::delete('/medicos/{id}', [MedicosController::class, 'destroy'])->name('medicos.destroy');

    // Panel del Médico
    Route::get('/medico/inicio', [MedicosController::class, 'inicio'])->name('medico.inicio');
    Route::get('/medico/horarios', [MedicosController::class, 'horarios'])->name('medico.horarios');
    Route::post('/medico/horarios', [MedicosController::class, 'guardarHorarios'])->name('medico.guardarHorarios');    Route::get('/medico/expedientes', [MedicosController::class, 'expedientes'])->name('medico.expedientes');
    Route::get('/medico/expedientes/{pacienteId}', [MedicosController::class, 'verExpediente'])->name('medico.verExpediente');
    Route::get('/medico/expedientes/{pacienteId}/lista', [MedicosController::class, 'listarExpedientesPaciente'])->name('medico.listarExpedientesPaciente');
    Route::post('/medico/citas/{id}/estado', [CitaController::class, 'actualizarEstado'])->name('medico.actualizarEstadoCita');
    Route::post('/medico/expedientes', [MedicosController::class, 'guardarExpediente'])->name('medico.guardarExpediente');
    Route::post('/medico/citas/{citaId}/notas', [MedicosController::class, 'guardarNotasMedicas'])->name('medico.guardarNotasMedicas');
});
