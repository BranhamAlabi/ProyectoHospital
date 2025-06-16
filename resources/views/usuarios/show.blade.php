@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-user me-2"></i>Detalle de Usuario</h2>
                <div class="d-flex gap-2">
                    @php
                        $userRoles = $usuario->roles->pluck('nombre')->toArray();
                        $isAdminOrMod = in_array('administrador', $userRoles) || in_array('moderador', $userRoles);
                        $currentUserRoles = session('usuario_rol', []);
                        $currentUserIsMod = in_array('moderador', $currentUserRoles);
                    @endphp

                    @if(!($isAdminOrMod && $currentUserIsMod))
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-light">
                            <i class="fas fa-edit me-2"></i>Editar Usuario
                        </a>
                    @endif
                    
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Avatar y nombre principal -->
            <div class="text-center mb-4 pb-4 border-bottom">
                <div class="avatar-large mx-auto mb-3">
                    {{ strtoupper(substr($usuario->nombre, 0, 2)) }}
                </div>
                <h3 class="mb-2 text-primary">{{ $usuario->nombre }}</h3>
                <p class="text-muted mb-3">{{ $usuario->correo }}</p>
                
                <!-- Estado del usuario -->
                <span class="badge fs-6 px-3 py-2 {{ $usuario->estado == 'activo' ? 'bg-success' : 'bg-secondary' }}">
                    <i class="fas {{ $usuario->estado == 'activo' ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                    {{ ucfirst($usuario->estado) }}
                </span>
            </div>

            <div class="row g-4">
                <!-- Información Personal -->
                <div class="col-lg-6">
                    <div class="card h-100 bg-light border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <div class="detail-item mb-3">
                                <label class="detail-label"><i class="fas fa-user me-2 text-primary"></i>Nombre Completo:</label>
                                <div class="detail-value">{{ $usuario->nombre }}</div>
                            </div>
                            <div class="detail-item mb-3">
                                <label class="detail-label"><i class="fas fa-envelope me-2 text-primary"></i>Correo Electrónico:</label>
                                <div class="detail-value">{{ $usuario->correo }}</div>
                            </div>
                            <div class="detail-item mb-3">
                                <label class="detail-label"><i class="fas fa-calendar-alt me-2 text-primary"></i>Fecha de Registro:</label>
                                <div class="detail-value">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'No disponible' }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-clock me-2 text-primary"></i>Última Actualización:</label>
                                <div class="detail-value">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'No disponible' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles y Permisos -->
                <div class="col-lg-6">
                    <div class="card h-100 bg-light border-0">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>Roles y Permisos</h5>
                        </div>
                        <div class="card-body">
                            <div class="detail-item mb-3">
                                <label class="detail-label"><i class="fas fa-shield-alt me-2 text-primary"></i>Rol(es) Asignado(s):</label>
                                <div class="detail-value">
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge me-2 mb-2 fs-6 px-3 py-2
                                            @if($rol->nombre == 'administrador') bg-danger
                                            @elseif($rol->nombre == 'moderador') bg-warning text-dark
                                            @elseif($rol->nombre == 'medico') bg-success
                                            @else bg-info text-dark
                                            @endif">
                                            <i class="fas 
                                                @if($rol->nombre == 'administrador') fa-crown
                                                @elseif($rol->nombre == 'moderador') fa-user-shield
                                                @elseif($rol->nombre == 'medico') fa-user-md
                                                @else fa-user
                                                @endif me-2"></i>
                                            {{ ucfirst($rol->nombre) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="detail-item mb-3">
                                <label class="detail-label"><i class="fas fa-toggle-on me-2 text-primary"></i>Estado de la Cuenta:</label>
                                <div class="detail-value">
                                    <span class="badge fs-6 px-3 py-2 {{ $usuario->estado == 'activo' ? 'bg-success' : 'bg-danger' }}">
                                        <i class="fas {{ $usuario->estado == 'activo' ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-key me-2 text-primary"></i>Acceso al Sistema:</label>
                                <div class="detail-value">
                                    @if($usuario->estado == 'activo')
                                        <span class="text-success"><i class="fas fa-check me-2"></i>Habilitado</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-times me-2"></i>Deshabilitado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones adicionales -->
            <div class="mt-4 pt-4 border-top">
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    @if(!($isAdminOrMod && $currentUserIsMod))
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-edit me-2"></i>Editar Usuario
                        </a>
                    @endif
                    
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list me-2"></i>Ver Todos los Usuarios
                    </a>
                    
                    <a href="{{ route('usuarios.create') }}" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Crear Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.3);
    }
    
    .detail-item {
        padding: 0.75rem 0;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .detail-value {
        font-size: 1rem;
        color: #6c757d;
        line-height: 1.5;
    }
    
    .card {
        border-radius: 0.75rem;
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .badge {
        font-size: 0.875em;
        padding: 0.5em 0.75em;
        border-radius: 0.5rem;
    }
    
    .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .border-bottom {
        border-color: rgba(0, 0, 0, 0.1) !important;
    }
    
    .border-top {
        border-color: rgba(0, 0, 0, 0.1) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
