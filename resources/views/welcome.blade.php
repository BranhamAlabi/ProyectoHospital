<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Hospitalario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Sistema de Gestión Hospitalaria</h4>
                    </div>
                    <div class="card-body text-center">
                        <h5>Bienvenido al Sistema</h5>
                        <p>Por favor, inicie sesión para continuar.</p>
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
