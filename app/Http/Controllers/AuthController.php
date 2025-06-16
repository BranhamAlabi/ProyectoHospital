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
    }    // Enviar enlace de recuperación al correo
    public function enviarEnlaceRecuperacion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email'
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debe ingresar un correo electrónico válido.'
        ]);

        $usuario = Usuarios::where('correo', $request->correo)->first();
        if (!$usuario) {
            // Por seguridad, no revelamos si el correo existe o no
            return back()->with('status', 'Si el correo está registrado en nuestro sistema, recibirás un enlace para restablecer tu contraseña.');
        }

        $token = Str::random(64);        try {
            // Eliminar tokens anteriores para este correo
            PasswordReset::where('correo', $request->correo)->delete();

            // Crear nuevo token
            PasswordReset::create([
                'correo' => $request->correo,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            // Crear el enlace de reset
            $enlaceReset = route('password.reset', ['token' => $token]);

            // Enviar correo
            $data = [
                'enlace' => $enlaceReset,
                'nombre' => $usuario->nombre
            ];
            
            Mail::send('emails.password_reset', $data, function($message) use ($request) {
                $message->to($request->correo);
                $message->subject('Restablecer contraseña - MediTech');
            });

            Log::info('Enlace de recuperación de contraseña enviado', [
                'correo' => $request->correo,
                'usuario_id' => $usuario->id,
                'token' => $token
            ]);

            return back()->with('status', 'Te hemos enviado un enlace para restablecer tu contraseña. Revisa tu bandeja de entrada y spam.');
            
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de recuperación de contraseña', [
                'correo' => $request->correo,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['correo' => 'Error al enviar el correo de recuperación. Intenta más tarde.']);
        }
    }// Mostrar formulario para resetear contraseña
    public function showResetForm($token)
    {
        // Buscar el token en la base de datos para obtener el correo
        $passwordReset = PasswordReset::where('token', $token)->first();
        
        if (!$passwordReset) {
            return redirect()->route('password.request')
                           ->withErrors(['token' => 'El enlace de recuperación es inválido o ha expirado.']);
        }
        
        // Verificar que no haya expirado (24 horas)
        if (Carbon::parse($passwordReset->created_at)->addDay()->isPast()) {
            // Eliminar token expirado
            $passwordReset->delete();
            return redirect()->route('password.request')
                           ->withErrors(['token' => 'El enlace de recuperación ha expirado. Solicita uno nuevo.']);
        }
          return view('resetear_contrasena', [
            'token' => $token,
            'correo' => $passwordReset->correo
        ]);
    }    // Procesar reset de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'correo' => 'required|email',
            'contrasena' => 'required|min:8|confirmed',
        ], [
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser válido.',
            'token.required' => 'Token de seguridad requerido.'
        ]);

        try {            // Verificar token
            $passwordReset = PasswordReset::where([
                'correo' => $request->correo,
                'token' => $request->token
            ])->first();

            if (!$passwordReset) {
                return back()->withErrors(['token' => 'El enlace de recuperación es inválido o ha expirado.']);
            }

            // Verificar que no haya expirado (24 horas)
            if (Carbon::parse($passwordReset->created_at)->addDay()->isPast()) {
                // Eliminar token expirado
                $passwordReset->delete();
                return back()->withErrors(['token' => 'El enlace de recuperación ha expirado. Solicita uno nuevo.']);
            }

            // Verificar que el usuario existe
            $usuario = Usuarios::where('correo', $request->correo)->first();
            if (!$usuario) {
                return back()->withErrors(['correo' => 'Usuario no encontrado.']);
            }

            // Actualizar contraseña
            $usuario->contrasena = Hash::make($request->contrasena);
            $usuario->save();            // Eliminar el token usado y todos los demás tokens de este usuario
            PasswordReset::where('correo', $request->correo)->delete();

            Log::info('Contraseña restablecida exitosamente', [
                'correo' => $request->correo,
                'usuario_id' => $usuario->id
            ]);

            return redirect()->route('login')
                           ->with('status', '¡Contraseña actualizada correctamente! Ya puedes iniciar sesión con tu nueva contraseña.');
                           
        } catch (\Exception $e) {
            Log::error('Error al restablecer contraseña', [
                'correo' => $request->correo,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['general' => 'Error interno. Intenta nuevamente o contacta al soporte técnico.']);
        }
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
