@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmoderador')

<div class="container mt-4">
    <h2>Detalle de Cita</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <strong>Estado:</strong> {{ ucfirst($cita->estado) }}
    </div>

    <div class="mb-3">
        <strong>Paciente:</strong> {{ $cita->paciente->nombre ?? 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Médico:</strong> {{ $cita->medico->nombre ?? 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Fecha:</strong> {{ $cita->fecha }}
    </div>

    <div class="mb-3">
        <strong>Hora:</strong> {{ $cita->hora }}
    </div>

    <div class="mb-3">
        <strong>Motivo:</strong> {{ $cita->motivo ?? 'No especificado' }}
    </div>

    <div class="mb-3">
        <strong>Clínica:</strong> {{ $cita->clinica->nombre ?? 'No especificada' }}
    </div>

    <div class="mb-3">
        <strong>Aprobado por:</strong> {{ $cita->actualizador->nombre ?? 'No especificado' }}
    </div>

    <form method="POST" action="{{ route('citas.updateStatus', $cita->id) }}">
        @csrf
        <div class="mb-3">
            <label for="estado" class="form-label">Actualizar Estado</label>
        <select name="estado" id="estado" class="form-select" required>
            <option value="aprobada" @selected($cita->estado == 'aprobada')>Aprobada</option>
            <option value="cancelada" @selected($cita->estado == 'cancelada')>Cancelada</option>
            <option value="pendiente" @selected($cita->estado == 'pendiente')>Pendiente</option>
            <option value="pendiente_reprogramacion" @selected($cita->estado == 'pendiente_reprogramacion')>Pendiente Reprogramación</option>
        </select>
        </div>

        <div class="mb-3">
            <label for="comentarios" class="form-label">Comentarios</label>
            <textarea name="comentarios" id="comentarios" class="form-control" rows="3">{{ old('comentarios', $cita->comentarios) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>

    {{-- Aquí se podría mostrar el historial de cambios para auditoría --}}
</div>
@endsection
