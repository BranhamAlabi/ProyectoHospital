@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navmedico')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-folder-open"></i> Expedientes de Pacientes</h5>
        </div>
        <div class="card-body">
            @if($pacientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Correo</th>
                            <th>Última Cita</th>
                            <th>Total Citas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pacientes as $paciente)
                        @php
                            $ultimaCita = $paciente->citasPaciente->first();
                            $totalCitas = $paciente->citasPaciente->count();
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $paciente->nombre }}</strong>
                            </td>
                            <td>{{ $paciente->correo }}</td>
                            <td>
                                @if($ultimaCita)
                                    {{ \Carbon\Carbon::parse($ultimaCita->fecha)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Sin citas</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $totalCitas }}</span>
                            </td>
                            <td>
                                @if($ultimaCita)
                                    <span class="badge bg-{{ 
                                        $ultimaCita->estado == 'completada' ? 'success' : 
                                        ($ultimaCita->estado == 'pendiente' ? 'warning' : 
                                        ($ultimaCita->estado == 'confirmada' ? 'primary' : 'danger')) 
                                    }}">
                                        {{ ucfirst($ultimaCita->estado) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sin citas</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('medico.verExpediente', $paciente->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver Expediente
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay pacientes registrados</h5>
                <p class="text-muted">Los pacientes aparecerán aquí una vez que tengan citas programadas contigo.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
