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


    .login-container {
        background: linear-gradient(135deg, #6e7b8b, #50c9c3);
        border-radius: 30px;
        padding: 40px;
        width: 300px;
        text-align: center;
        box-shadow: 0 8px 16px rgba(0,0,0,0.25);
        margin: 0 auto;
        margin-top: 100px;
    }

    .login-container h2 {
        color: black;
        margin-bottom: 25px;
        font-size: 24px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: none;
        border-radius: 8px;
        background-color: #556671;
        color: white;
    }

    .login-container button {
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

    .login-container button:hover {
        background: linear-gradient(to right, #3a78d2, #4c9cde);
    }

    .login-container a {
        display: block;
        margin-top: 15px;
        color: #64c7cc;
        text-decoration: none;
        font-size: 14px;
    }

    .login-container a:hover {
        text-decoration: underline;
    }
</style>


<div class="login-container">
    <h2><b>Login</b></h2>

    <form method="POST" action="/">
        @csrf
        <input type="text" name="username" placeholder="Nombre, Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar sesión</button>
    </form>

    <a href="/Registrar">Registrarse</a>
</div>






@endsection
