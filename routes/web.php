<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



use Illuminate\Http\Request;

Route::get('/Login', function () {
    return view('Login');
});

Route::post('/', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    // Aquí puedes agregar lógica de autenticación
    return "Usuario: $username, Contraseña: $password";
});




Route::get('/Registrar', function(){
    return view("Registrar");
});


Route::get('/Inicio', function(){
    return view("Inicio");
});


Route::get("/AgenCita", function(){
    return view("AgenCita");
});




// Rutas de Ronald

use App\Http\Controllers\UsuariosController;

Route::resource('Usuarios', UsuariosController::class);

