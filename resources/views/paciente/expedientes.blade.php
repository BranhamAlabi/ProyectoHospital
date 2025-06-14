@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-folder-medical me-2"></i>Mis Expedientes Médicos
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('paciente.inicio') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Expedientes Médicos</li>
    </ol>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h4 class="text-primary">{{ $totalConsultas }}</h4>
                    <p class="mb-0">Total de Consultas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h4 class="text-success">{{ $especialidadesVisitadas }}</h4>
                    <p class="mb-0">Especialidades Visitadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning">{{ $medicosVisitados }}</h4>
                    <p class="mb-0">Médicos Consultados</p>
                </div>
            </div>
        </div>
    </div>

    @if($expedientes->count() > 0)        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="buscarExpediente" class="form-label">Buscar en diagnóstico/tratamiento:</label>
                        <input type="text" id="buscarExpediente" class="form-control" placeholder="Ej: diabetes, hipertensión...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filtroMedico" class="form-label">Médico:</label>
                        <select id="filtroMedico" class="form-select">
                            <option value="">Todos los médicos</option>
                            @foreach($expedientes->pluck('medico')->unique('id') as $medico)
                                <option value="{{ $medico->id }}">
                                    Dr. {{ $medico->usuario->nombre ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filtroFecha" class="form-label">Fecha de consulta:</label>
                        <input type="date" id="filtroFecha" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filtroEspecialidad" class="form-label">Especialidad:</label>
                        <select id="filtroEspecialidad" class="form-select">
                            <option value="">Todas las especialidades</option>
                            @foreach($expedientes->pluck('medico.especialidades')->flatten()->unique('especialidad') as $especialidad)
                                <option value="{{ $especialidad->especialidad }}">
                                    {{ $especialidad->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times me-1"></i> Limpiar Filtros
                        </button>
                        <span id="resultadosCount" class="ms-3 text-muted"></span>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-outline-primary" onclick="exportarResultados()">
                            <i class="fas fa-download me-1"></i> Exportar Resultados
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Expedientes -->
        <div id="expedientesList">
            @foreach($expedientes as $expediente)                <div class="card mb-4 expediente-card" 
                     data-medico="{{ $expediente->medico_id }}" 
                     data-fecha="{{ $expediente->fecha }}"
                     data-medico-nombre="{{ $expediente->medico->usuario->nombre ?? '' }}"
                     data-especialidad="{{ $expediente->medico->especialidades->first()->especialidad ?? '' }}"
                     data-diagnostico="{{ $expediente->medicalNotes->pluck('diagnostico')->implode(' ') }}"
                     data-tratamiento="{{ $expediente->medicalNotes->pluck('tratamiento')->implode(' ') }}"
                     data-observaciones="{{ $expediente->medicalNotes->pluck('observaciones')->implode(' ') }}"
                     data-motivo="{{ $expediente->motivo }}"
                     data-clinica="{{ $expediente->clinica->nombre ?? '' }}">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($expediente->fecha)->format('d/m/Y') }}
                                a las {{ $expediente->hora }}
                            </h6>
                            <span class="badge bg-success">{{ ucfirst($expediente->estado) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Información de la Consulta -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong><i class="fas fa-user-md me-2"></i>Médico:</strong><br>
                                    <span class="text-muted">Dr. {{ $expediente->medico->usuario->nombre ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-stethoscope me-2"></i>Especialidad:</strong><br>
                                    <span class="text-muted">{{ $expediente->medico->especialidades->first()->especialidad ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-hospital me-2"></i>Clínica:</strong><br>
                                    <span class="text-muted">{{ $expediente->clinica->nombre ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="fas fa-comment-medical me-2"></i>Motivo de Consulta:</strong><br>
                                    <p class="text-muted mb-0">{{ $expediente->motivo }}</p>
                                </div>
                            </div>

                            <!-- Notas Médicas -->
                            <div class="col-md-6">
                                @if($expediente->medicalNotes->count() > 0)
                                    @foreach($expediente->medicalNotes as $nota)
                                        <div class="border rounded p-3 mb-3 bg-light">
                                            <h6 class="text-info mb-3">
                                                <i class="fas fa-notes-medical me-2"></i>Expediente Médico
                                            </h6>
                                            
                                            @if($nota->diagnostico)
                                                <div class="mb-2">
                                                    <strong>Diagnóstico:</strong><br>
                                                    <span class="text-success">{{ $nota->diagnostico }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($nota->tratamiento)
                                                <div class="mb-2">
                                                    <strong>Tratamiento:</strong><br>
                                                    <span class="text-primary">{{ $nota->tratamiento }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($nota->observaciones)
                                                <div class="mb-2">
                                                    <strong>Observaciones:</strong><br>
                                                    <span class="text-muted">{{ $nota->observaciones }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($nota->notas_adicionales)
                                                <div class="mb-2">
                                                    <strong>Recomendaciones:</strong><br>
                                                    <span class="text-warning">{{ $nota->notas_adicionales }}</span>
                                                </div>
                                            @endif
                                            
                                            <hr>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Registrado: {{ $nota->created_at ? $nota->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </small>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="border rounded p-3 bg-light text-center">
                                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Sin notas médicas registradas</p>
                                        <small class="text-muted">El médico no ha agregado expediente para esta consulta</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Consulta realizada el {{ \Carbon\Carbon::parse($expediente->fecha)->format('d/m/Y') }}
                            </small>
                            @if($expediente->medicalNotes->count() > 0)
                                <button class="btn btn-sm btn-outline-primary" onclick="imprimirExpediente({{ $expediente->id }})">
                                    <i class="fas fa-print me-1"></i>Imprimir
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">No tienes expedientes médicos registrados</h4>
                        <p class="text-muted">
                            Tus expedientes médicos aparecerán aquí después de tus consultas médicas.
                            Los médicos crearán notas médicas durante o después de tu consulta.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-1"></i>Agendar Nueva Cita
                            </a>
                            <a href="{{ route('paciente.inicio') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
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
    // Variables globales
    let expedientesOriginales = [];
    let expedientesFiltrados = [];

    // Función principal de filtrado
    function filtrarExpedientes() {
        const busqueda = document.getElementById('buscarExpediente').value.toLowerCase().trim();
        const medico = document.getElementById('filtroMedico').value;
        const fecha = document.getElementById('filtroFecha').value;
        const especialidad = document.getElementById('filtroEspecialidad').value;
        const expedientes = document.querySelectorAll('.expediente-card');

        let visibles = 0;
        expedientesFiltrados = [];

        expedientes.forEach(expediente => {
            // Obtener datos del expediente
            const diagnostico = expediente.dataset.diagnostico.toLowerCase();
            const tratamiento = expediente.dataset.tratamiento.toLowerCase();
            const observaciones = expediente.dataset.observaciones.toLowerCase();
            const motivo = expediente.dataset.motivo.toLowerCase();
            const medicoId = expediente.dataset.medico;
            const fechaExpediente = expediente.dataset.fecha;
            const especialidadExpediente = expediente.dataset.especialidad;
            const medicoNombre = expediente.dataset.medicoNombre.toLowerCase();
            const clinica = expediente.dataset.clinica.toLowerCase();

            let mostrar = true;

            // Filtro por búsqueda de texto (busca en múltiples campos)
            if (busqueda) {
                const textoCompleto = `${diagnostico} ${tratamiento} ${observaciones} ${motivo} ${medicoNombre} ${clinica}`;
                if (!textoCompleto.includes(busqueda)) {
                    mostrar = false;
                }
            }

            // Filtro por médico
            if (medico && medicoId !== medico) {
                mostrar = false;
            }

            // Filtro por fecha
            if (fecha && fechaExpediente !== fecha) {
                mostrar = false;
            }

            // Filtro por especialidad
            if (especialidad && especialidadExpediente !== especialidad) {
                mostrar = false;
            }

            // Mostrar u ocultar el expediente
            if (mostrar) {
                expediente.style.display = 'block';
                expediente.style.opacity = '1';
                expediente.style.transform = 'translateY(0)';
                visibles++;
                expedientesFiltrados.push(expediente);
            } else {
                expediente.style.display = 'none';
            }
        });

        // Actualizar contador de resultados
        actualizarContadorResultados(visibles, expedientes.length);

        // Mostrar animación para resultados visibles
        setTimeout(() => {
            expedientesFiltrados.forEach((expediente, index) => {
                expediente.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                expediente.style.opacity = '0';
                expediente.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    expediente.style.opacity = '1';
                    expediente.style.transform = 'translateY(0)';
                }, index * 50);
            });
        }, 100);

        // Mostrar mensaje si no hay resultados
        mostrarMensajeSinResultados(visibles === 0);
    }

    // Función para actualizar el contador de resultados
    function actualizarContadorResultados(visibles, total) {
        const contador = document.getElementById('resultadosCount');
        if (visibles === total) {
            contador.textContent = `Mostrando ${total} expediente${total !== 1 ? 's' : ''}`;
            contador.className = 'ms-3 text-muted';
        } else {
            contador.textContent = `Mostrando ${visibles} de ${total} expediente${total !== 1 ? 's' : ''}`;
            contador.className = 'ms-3 text-info fw-bold';
        }
    }

    // Función para mostrar mensaje cuando no hay resultados
    function mostrarMensajeSinResultados(mostrar) {
        let mensajeExistente = document.getElementById('mensajeSinResultados');
        
        if (mostrar && !mensajeExistente) {
            const mensaje = document.createElement('div');
            mensaje.id = 'mensajeSinResultados';
            mensaje.className = 'alert alert-info text-center';
            mensaje.innerHTML = `
                <i class="fas fa-search fa-2x mb-3"></i>
                <h5>No se encontraron resultados</h5>
                <p class="mb-0">Intenta ajustar los filtros de búsqueda para encontrar lo que buscas.</p>
            `;
            document.getElementById('expedientesList').appendChild(mensaje);
        } else if (!mostrar && mensajeExistente) {
            mensajeExistente.remove();
        }
    }

    // Función para limpiar todos los filtros
    function limpiarFiltros() {
        document.getElementById('buscarExpediente').value = '';
        document.getElementById('filtroMedico').value = '';
        document.getElementById('filtroFecha').value = '';
        document.getElementById('filtroEspecialidad').value = '';
        
        // Mostrar todos los expedientes
        const expedientes = document.querySelectorAll('.expediente-card');
        expedientes.forEach(expediente => {
            expediente.style.display = 'block';
        });
        
        // Actualizar contador
        actualizarContadorResultados(expedientes.length, expedientes.length);
        
        // Ocultar mensaje de sin resultados
        mostrarMensajeSinResultados(false);
        
        // Animación de reaparición
        expedientes.forEach((expediente, index) => {
            expediente.style.opacity = '0';
            expediente.style.transform = 'translateY(20px)';
            setTimeout(() => {
                expediente.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                expediente.style.opacity = '1';
                expediente.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    // Función para exportar resultados (base para futura implementación)
    function exportarResultados() {
        const expedientesVisibles = expedientesFiltrados.length || document.querySelectorAll('.expediente-card[style*="block"], .expediente-card:not([style*="none"])').length;
        
        if (expedientesVisibles === 0) {
            alert('No hay resultados para exportar. Ajusta los filtros primero.');
            return;
        }
        
        // Aquí se puede implementar la exportación real
        alert(`Exportando ${expedientesVisibles} expediente${expedientesVisibles !== 1 ? 's' : ''}...`);
        console.log('Expedientes a exportar:', expedientesFiltrados);
    }

    // Función para imprimir expediente individual
    function imprimirExpediente(citaId) {
        if (confirm('¿Deseas imprimir este expediente médico?')) {
            // Aquí se puede implementar la función de impresión real
            alert('Preparando impresión del expediente #' + citaId + '...');
        }
    }

    // Función de búsqueda con debounce para mejor rendimiento
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Función para resaltar texto encontrado
    function resaltarTextoEncontrado(texto, busqueda) {
        if (!busqueda) return texto;
        const regex = new RegExp(`(${busqueda})`, 'gi');
        return texto.replace(regex, '<mark>$1</mark>');
    }

    // Inicialización cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar event listeners con debounce para mejor rendimiento
        const debouncedFilter = debounce(filtrarExpedientes, 300);
        
        document.getElementById('buscarExpediente').addEventListener('input', debouncedFilter);
        document.getElementById('filtroMedico').addEventListener('change', filtrarExpedientes);
        document.getElementById('filtroFecha').addEventListener('change', filtrarExpedientes);
        document.getElementById('filtroEspecialidad').addEventListener('change', filtrarExpedientes);

        // Contar expedientes iniciales
        const expedientes = document.querySelectorAll('.expediente-card');
        actualizarContadorResultados(expedientes.length, expedientes.length);

        // Animación inicial de carga
        expedientes.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Agregar tooltips informativos
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            new bootstrap.Tooltip(element);
        });

        // Almacenar expedientes originales para referencia
        expedientesOriginales = Array.from(expedientes);
    });

    // Función para estadísticas en tiempo real
    function actualizarEstadisticas() {
        const expedientesVisibles = document.querySelectorAll('.expediente-card[style*="block"], .expediente-card:not([style*="none"])');
        
        // Aquí se podrían actualizar estadísticas dinámicas
        console.log(`Expedientes actualmente visibles: ${expedientesVisibles.length}`);
    }
</script>
@endsection


