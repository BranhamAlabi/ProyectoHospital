<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediTech!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #374563; /* Fondo principal */
      font-family: Arial, sans-serif;
      color: white;
    }
    header {
      background-color: #12153b;
      padding: 1rem 2rem;
      font-weight: bold;
      font-size: 1.5rem;
      font-family: 'Impact', sans-serif;
      letter-spacing: 2px;
      color: white;
    }
    .btn-green {
      background-color: #2e6136;
      border: none;
      color: white;
    }
    .btn-green:hover {
      background-color: #3d7d49;
      color: white;
    }
    .btn-purple {
      background-color: #5c59e6;
      border: none;
      color: white;
    }
    .btn-purple:hover {
      background-color: #7b78f2;
      color: white;
    }
    .filter-label {
      color: white;
      font-weight: 500;
      line-height: 2.4;
    }
    .table thead {
      background-color: #12153b;
    }
    .table thead th {
      color: white;
    }
    .table tbody tr td {
      vertical-align: middle;
    }
    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.9rem;
      }
      header {
        font-size: 1.2rem;
        padding: 0.8rem 1rem;
      }
    }
  </style>
</head>
<body>


<div class="container py-4">
  <header class="bg-dark text-white p-3 mb-4" style="font-family: 'Impact', sans-serif; font-size: 1.5rem;">
    MEDITECH!
  </header>

  <div class="mb-3">
    <a href="#" class="btn btn-success">Crear Nuevo Usuario</a>
  </div>

  <form method="GET" action="#" class="row g-3 align-items-center mb-4">
    <div class="col-auto">
      <label for="rol" class="form-label text-white">Filtro:</label>
      <select name="rol" id="rol" class="form-select">
        <option value="">Rol</option>
        @foreach($roles as $rol)
          <option value="{{ $rol }}" @selected(request('rol') == $rol)>{{ ucfirst($rol) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-auto">
      <label for="estado" class="form-label text-white">Estado:</label>
      <select name="estado" id="estado" class="form-select">
        <option value="">Estado</option>
        <option value="activo" @selected(request('estado') == 'activo')>Activo</option>
        <option value="inactivo" @selected(request('estado') == 'inactivo')>Inactivo</option>
      </select>
    </div>
    <div class="col-auto">
      <label for="buscar" class="form-label text-white">Buscar:</label>
      <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o correo..." />
    </div>
    <div class="col-auto align-self-end">
      <button type="submit" class="btn btn-success">Buscar</button>
    </div>
  </form>

  <div class="table-responsive bg-white rounded shadow">
    <table class="table mb-0">
      <thead class="table-dark">
        <tr>
          <th>Nombre</th>
          <th>Rol(es)</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->nombre }}</td>
            <td>
              @foreach($usuario->roles as $rol)
                <span class="badge bg-primary text-white">{{ ucfirst($rol->nombre) }}</span>
              @endforeach
            </td>
            <td>{{ ucfirst($usuario->estado) }}</td>
            <td>
              <a href="#" class="btn btn-primary btn-sm">Ver...</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">No se encontraron usuarios.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $usuarios->links() }}
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
