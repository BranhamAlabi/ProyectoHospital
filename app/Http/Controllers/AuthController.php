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

        // 5. Guardar datos esenciales en sesión (opcional)
        $roles = $usuario->roles()->pluck('nombre')->map(function($r) {
            return strtolower($r);
        })->toArray();

        session([
            'usuario_id'     => $usuario->id,
            'usuario_nombre' => $usuario->nombre,
            'usuario_rol'    => $roles,
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

    // Mostrar formulario de “recuperar contraseña”
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
        PasswordReset::where('correo', $request->correo)->delete();
        // Crear uno nuevo
        PasswordReset::create([
            'correo' => $request->correo,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $resetLink = route('password.reset', ['token' => $token]);

        // Enviar correo (usa la vista emails.password_reset)
        Mail::send('emails.password_reset', [
            'usuario' => $usuario,
            'resetLink' => $resetLink,
        ], function($message) use ($usuario) {
            $message->to($usuario->correo, $usuario->nombre)
                    ->subject('Recupera tu contraseña - MediTech');
        });

        return back()->with('status', 'Se ha enviado un enlace de recuperación a tu correo.');
    }

    // Mostrar formulario para ingresar nueva contraseña
    public function showResetForm($token)
    {
        $reset = PasswordReset::where('token', $token)
                ->where('created_at', '>=', Carbon::now()->subHour())
                ->first();

        if (!$reset) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'El enlace de recuperación ha expirado o es inválido.']);
        }

        return view('resetear_contrasena', [
            'token' => $token,
            'correo' => $reset->correo
        ]);
    }

    // Procesar restablecimiento de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'token' => 'required',
            'contrasena' => 'required|min:8|confirmed',
        ]);

        $reset = PasswordReset::where('correo', $request->correo)
            ->where('token', $request->token)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->first();

        if (!$reset) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'El enlace de recuperación ha expirado o es inválido.']);
        }

        $usuario = Usuarios::where('correo', $request->correo)->first();
        if (!$usuario) {
            return redirect()->route('password.request')
                ->withErrors(['correo' => 'No existe ninguna cuenta con ese correo.']);
        }

        // Actualizar contraseña
        $usuario->contrasena = Hash::make($request->contrasena);
        $usuario->save();

        // Borrar el token (para que no se reutilice)
        PasswordReset::where('correo', $request->correo)->delete();

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

        // Crear usuario nuevo con rol “paciente” y estado activo
        $usuario = new Usuarios();
        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        $usuario->estado = 'activo';
        $usuario->contrasena = Hash::make($request->contrasena);
        $usuario->save();

        // Asignar rol “paciente” (suponemos que el rol con id=4 es paciente)
        $usuario->roles()->sync([4]);

        return redirect()->route('login')->with('status', 'Registro exitoso. Por favor inicia sesión.');
    }
}
