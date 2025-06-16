<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Medico;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Mostrar formulario de inicio de sesión
    public function showLogin()
    {
        return view('Login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // 1. Validar que vengan correo y contrasena
        $request->validate([
            'correo'    => 'required|email',
            'contrasena'=> 'required|string',
        ]);

        // 2. Buscar el usuario por correo y que esté activo
        $usuario = Usuarios::where('correo', $request->correo)
                           ->where('estado', 'activo')
                           ->first();

        // 3. Si no existe o la contraseña no coincide, redirigir con error
        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return back()
                ->withErrors(['correo' => 'Credenciales inválidas o usuario inactivo.'])
                ->withInput(['correo' => $request->correo]);
        }

        // 4. Autenticar al usuario usando Laravel auth
        Auth::login($usuario);

        // 5. Guardar datos esenciales en sesión de manera explícita como array
        $roles = $usuario->roles()->pluck('nombre')->map(function($r) {
            return strtolower($r);
        })->toArray(); // Aseguramos que sea un array

        session([
            'usuario_id' => $usuario->id,
            'usuario_nombre' => $usuario->nombre,
            'usuario_rol' => $roles // Guardamos el array de roles
        ]);

        // 6. Redirigir a la URL anterior o a la gestión inicio según rol
        Log::info('Login roles detected: ' . implode(',', $roles) . ' for user: ' . $usuario->id);

        if (in_array('administrador', $roles) || in_array('moderador', $roles)) {
            return redirect()->intended(route('gestion.inicio'));
        } elseif (in_array('medico', $roles)) {
            return redirect()->intended(route('medico.inicio'));
        } elseif (in_array('paciente', $roles)) {
            return redirect()->intended(route('gestion.inicioPaciente'));
        }
        return redirect()->intended(route('usuarios.index'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        // Vaciar toda la sesión
        $request->session()->flush();

        // Redirigir al login
        return redirect()->route('login');
    }

    // Mostrar formulario de "recuperar contraseña"
    public function showRecuperarContrasena()
    {
        return view('recuperar_contrasena');
    }

    // Enviar enlace de recuperación al correo
    public function enviarEnlaceRecuperacion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email'
        ]);

        $usuario = Usuarios::where('correo', $request->correo)->first();
        if (!$usuario) {
            return back()->withErrors(['correo' => 'No existe ninguna cuenta con ese correo.']);
        }

        $token = Str::random(64);

        // Eliminar tokens anteriores
        PasswordReset::where('email', $request->correo)->delete();

        // Crear nuevo token
        PasswordReset::create([
            'email' => $request->correo,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Crear el enlace de reset
        $enlaceReset = route('password.reset', ['token' => $token]);

        // Enviar correo
        try {
            $data = [
                'enlace' => $enlaceReset,
                'nombre' => $usuario->nombre
            ];
            
            Mail::send('emails.password_reset', $data, function($message) use ($request) {
                $message->to($request->correo);
                $message->subject('Restablecer contraseña - MediTech');
            });

            return back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña.');
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de recuperación: ' . $e->getMessage());
            return back()->withErrors(['correo' => 'Error al enviar el correo. Intenta más tarde.']);
        }
    }

    // Mostrar formulario para resetear contraseña
    public function showResetForm($token)
    {
        return view('resetear_contrasena', ['token' => $token]);
    }

    // Procesar reset de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email',
            'contrasena' => 'required|min:8|confirmed',
        ]);

        // Verificar token
        $passwordReset = PasswordReset::where([
            'email' => $request->correo,
            'token' => $request->token
        ])->first();

        if (!$passwordReset) {
            return back()->withErrors(['correo' => 'Token inválido o expirado.']);
        }

        // Verificar que no haya expirado (24 horas)
        if (Carbon::parse($passwordReset->created_at)->addDay()->isPast()) {
            return back()->withErrors(['correo' => 'El token ha expirado.']);
        }

        // Actualizar contraseña
        $usuario = Usuarios::where('correo', $request->correo)->first();
        if (!$usuario) {
            return back()->withErrors(['correo' => 'Usuario no encontrado.']);
        }

        $usuario->contrasena = Hash::make($request->contrasena);
        $usuario->save();

        // Eliminar el token usado
        PasswordReset::where('email', $request->correo)->delete();

        return redirect()->route('login')
                         ->with('status', '¡Contraseña actualizada correctamente! Ya puedes iniciar sesión.');
    }

    // Mostrar formulario de registro (ya tenías Registrar.blade.php)
    public function showRegister()
    {
        return view('Registrar');
    }

    // Procesar registro de paciente
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:8|confirmed',
        ]);

        // Crear usuario nuevo con rol "paciente" y estado activo
        $usuario = new Usuarios();
        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        $usuario->estado = 'activo';
        $usuario->contrasena = Hash::make($request->contrasena);
        $usuario->save();

        // Asignar rol "paciente" (suponemos que el rol con id=4 es paciente)
        $usuario->roles()->sync([4]);

        // 📧 Enviar correo de bienvenida
        try {
            Mail::to($usuario->correo)->send(new \App\Mail\BienvenidaMeditech($usuario));
            Log::info('Correo de bienvenida enviado exitosamente', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de bienvenida', [
                'usuario_id' => $usuario->id,
                'correo' => $usuario->correo,
                'error' => $e->getMessage()
            ]);
            // No fallar el registro si hay error con el correo
        }

        return redirect()->route('login')->with('status', 'Registro exitoso. Te hemos enviado un correo de bienvenida. Por favor inicia sesión.');
    }
}
