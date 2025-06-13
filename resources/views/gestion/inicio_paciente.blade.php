@extends('Plantillas.sesion')

@section('Contenido')
@include('Plantillas.navpaciente')

<div class="container mt-4">
    <h2>Panel Principal del Paciente</h2>

    <!-- Resumen de Citas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Resumen de Citas</h5>
            <div>
                <label for="filtroFecha">Filtrar por fecha:</label>
                <select id="filtroFecha" class="form-select d-inline-block w-auto">
                    <option value="proximas" selected>Próximas</option>
                    <option value="pasadas">Pasadas</option>
                </select>

                <label for="filtroEstado" class="ms-3">Filtrar por estado:</label>
                <select id="filtroEstado" class="form-select d-inline-block w-auto">
                    <option value="todos" selected>Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="cancelada">Cancelada</option>
                </select>

                <a href="{{ route('citas.index') }}" class="btn btn-primary btn-sm ms-3">Agendar Nueva Cita</a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0" id="tablaCitas">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Médico</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citasProximas as $cita)
                    <tr data-fecha="{{ $cita->fecha }}" data-estado="{{ strtolower($cita->estado) }}">
                        <td>{{ $cita->fecha }}</td>
                        <td>{{ $cita->hora }}</td>
                        <td>{{ $cita->medico->usuario->nombre ?? 'N/A' }}</td>
                        <td>
                            @if($cita->medico && $cita->medico->especialidades)
                                {{ $cita->medico->especialidades->pluck('especialidad')->join(', ') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ ucfirst($cita->estado) }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-reprogramar" data-id="{{ $cita->id }}">Reprogramar</button>
                            <button class="btn btn-danger btn-sm btn-cancelar" data-id="{{ $cita->id }}">Cancelar</button>
                        </td>
                    </tr>
                    @endforeach
                    @foreach ($citasPasadas as $cita)
                    <tr data-fecha="{{ $cita->fecha }}" data-estado="{{ strtolower($cita->estado) }}">
                        <td>{{ $cita->fecha }}</td>
                        <td>{{ $cita->hora }}</td>
                        <td>{{ $cita->medico->usuario->nombre ?? 'N/A' }}</td>
                        <td>
                            @if($cita->medico && $cita->medico->especialidades)
                                {{ $cita->medico->especialidades->pluck('especialidad')->join(', ') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ ucfirst($cita->estado) }}</td>
                        <td>
                            <!-- No actions for past appointments -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Acceso al Expediente Médico -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Expediente Médico</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('paciente.expediente') }}" class="btn btn-info">Ver Expediente Médico</a>
            <!-- Optional PDF download button -->
            <a href="{{ route('paciente.expediente.pdf') }}" class="btn btn-secondary ms-2">Descargar Informe PDF</a>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Acciones Rápidas</h5>
        </div>
        <div class="card-body">
            <form id="formSubirDocumento" enctype="multipart/form-data" method="POST" action="{{ route('paciente.subirDocumento') }}">
                @csrf
                <div class="mb-3">
                    <label for="documento" class="form-label">Subir Documento Médico</label>
                    <input type="file" class="form-control" id="documento" name="documento" required>
                </div>
                <button type="submit" class="btn btn-primary">Subir Documento</button>
            </form>
        </div>
    </div>

    <!-- Notificaciones Integradas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Notificaciones</h5>
        </div>
        <div class="card-body">
            @if($notificaciones->isEmpty())
                <p>No hay notificaciones nuevas.</p>
            @else
                <ul class="list-group">
                    @foreach($notificaciones as $notificacion)
                        <li class="list-group-item">
                            {{ $notificacion->titulo ?? 'Notificación' }} - {{ $notificacion->created_at->format('d/m/Y H:i') }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('paciente.notificaciones') }}" class="btn btn-link mt-2">Ver historial completo</a>
            @endif
        </div>
    </div>

    <!-- Datos del Perfil -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Datos del Perfil</h5>
        </div>
        <div class="card-body">
            <form id="formPerfil" method="POST" action="{{ route('paciente.actualizarPerfil') }}">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $perfil->nombre }}" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="{{ $perfil->correo }}" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $perfil->telefono ?? '' }}">
                </div>
                <button type="submit" class="btn btn-success">Actualizar Perfil</button>
            </form>

            <hr>

            <form id="formCambiarContrasena" method="POST" action="{{ route('paciente.cambiarContrasena') }}">
                @csrf
                <div class="mb-3">
                    <label for="password_actual" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                </div>
                <div class="mb-3">
                    <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password_nueva" name="password_nueva" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                </div>
                <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript para filtrar citas por fecha y estado
    document.addEventListener('DOMContentLoaded', function() {
        const filtroFecha = document.getElementById('filtroFecha');
        const filtroEstado = document.getElementById('filtroEstado');
        const tablaCitas = document.getElementById('tablaCitas').getElementsByTagName('tbody')[0];

        function filtrarCitas() {
            const fechaValor = filtroFecha.value;
            const estadoValor = filtroEstado.value;

            for (let row of tablaCitas.rows) {
                const fecha = row.getAttribute('data-fecha');
                const estado = row.getAttribute('data-estado');

                let mostrar = true;

                if (fechaValor === 'proximas' && fecha < new Date().toISOString().slice(0,10)) {
                    mostrar = false;
                } else if (fechaValor === 'pasadas' && fecha >= new Date().toISOString().slice(0,10)) {
                    mostrar = false;
                }

                if (estadoValor !== 'todos' && estado !== estadoValor) {
                    mostrar = false;
                }

                row.style.display = mostrar ? '' : 'none';
            }
        }

        filtroFecha.addEventListener('change', filtrarCitas);
        filtroEstado.addEventListener('change', filtrarCitas);
        filtrarCitas();

        // Event listeners para botones de reprogramar y cancelar
        document.querySelectorAll('.btn-reprogramar').forEach(function(button) {
            button.addEventListener('click', function() {
                var citaId = this.getAttribute('data-id');
                if (confirm('¿Desea reprogramar esta cita?')) {
                    // Aquí se puede implementar la lógica para reprogramar
                    alert('Funcionalidad de reprogramar pendiente de implementación.');
                }
            });
        });

        document.querySelectorAll('.btn-cancelar').forEach(function(button) {
            button.addEventListener('click', function() {
                var citaId = this.getAttribute('data-id');
                if (confirm('¿Desea cancelar esta cita?')) {
                    // Aquí se puede implementar la lógica para cancelar
                    alert('Funcionalidad de cancelar pendiente de implementación.');
                }
            });
        });
    });
</script>
@endsection
