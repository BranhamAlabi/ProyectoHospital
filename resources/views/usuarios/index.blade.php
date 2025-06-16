@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-users me-2"></i>Gestión de Usuarios</h2>
                <a href="{{ route('usuarios.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>Crear Nuevo Usuario
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filtros -->
            <form method="GET" action="{{ route('usuarios.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="rol" class="form-label">Filtrar por Rol</label>
                    <select name="rol" id="rol" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $rol)
                            <option value="{{ $rol }}" @selected(request('rol') == $rol)>{{ ucfirst($rol) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Filtrar por Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" @selected(request('estado') == 'activo')>Activo</option>
                        <option value="inactivo" @selected(request('estado') == 'inactivo')>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="buscar" class="form-label">Buscar Usuario</label>
                    <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" 
                           class="form-control" placeholder="Buscar por nombre o correo...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Buscar
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-broom me-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th><i class="fas fa-user me-1"></i>Nombre</th>
                            <th><i class="fas fa-envelope me-1"></i>Correo</th>
                            <th><i class="fas fa-user-tag me-1"></i>Rol(es)</th>
                            <th><i class="fas fa-toggle-on me-1"></i>Estado</th>
                            <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($usuario->nombre, 0, 2)) }}
                                        </div>
                                        <strong>{{ $usuario->nombre }}</strong>
                                    </div>
                                </td>
                                <td>{{ $usuario->correo }}</td>
                                <td>
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge 
                                            @if($rol->nombre == 'administrador') bg-danger
                                            @elseif($rol->nombre == 'moderador') bg-warning text-dark
                                            @elseif($rol->nombre == 'medico') bg-success
                                            @else bg-info text-dark
                                            @endif me-1">
                                            {{ ucfirst($rol->nombre) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $usuario->estado == 'activo' ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="fas {{ $usuario->estado == 'activo' ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('usuarios.show', $usuario->id) }}" 
                                       class="btn btn-sm btn-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-users fa-2x mb-2 text-muted"></i>
                                    <p class="text-muted">No se encontraron usuarios</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $usuarios->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 0.8rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    .btn {
        border-radius: 0.375rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
