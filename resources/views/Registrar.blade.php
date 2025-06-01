@extends ("Plantillas.sesion")
@section("Contenido")

<style>
        body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background-color: #1a2a49;
        min-height: 100vh;
        }

    .register-container {
   background: linear-gradient(135deg, #6e7b8b, #50c9c3);
        border-radius: 30px;
        padding: 40px;
        width: 300px;
        text-align: center;
        box-shadow: 0 8px 16px rgba(0,0,0,0.25);
        margin: 0 auto;
        margin-top: 100px;
    }

    .register-container h2 {
        color: black;
        margin-bottom: 25px;
        font-size: 24px;
    }

    .register-container input[type="text"],
    .register-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: none;
        border-radius: 8px;
        background-color: #556671;
        color: white;
    }

    .register-container button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(to right, #4a90e2, #5cacee);
        color: white;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .register-container button:hover {
        background: linear-gradient(to right, #3a78d2, #4c9cde);
    }
</style>

<div class="page-container">
    <div class="register-container">
        <h2><b>Registrar</b></h2>

        <form method="POST" action="/Registrar">
            @csrf
            <input type="text" name="username" placeholder="Nombre, Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
    </div>
</div>

@endsection
