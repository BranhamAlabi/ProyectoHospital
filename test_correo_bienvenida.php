<?php

/**
 * Script de prueba para validar el envío de correo de bienvenida
 * Ejecutar con: php test_correo_bienvenida.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Usuarios;
use App\Mail\BienvenidaMeditech;
use Illuminate\Support\Facades\Mail;

echo "📧 PROBANDO CORREO DE BIENVENIDA MEDITECH\n";
echo "==========================================\n\n";

try {
    // Buscar un usuario de prueba o crear uno temporal
    $usuario = Usuarios::where('correo', 'test@example.com')->first();
    
    if (!$usuario) {
        echo "ℹ️  No hay usuario de prueba, simulando datos...\n";
        $usuario = new Usuarios();
        $usuario->nombre = 'Usuario de Prueba';
        $usuario->correo = 'test@example.com';
        $usuario->id = 999; // ID temporal
    }

    echo "👤 Usuario de prueba:\n";
    echo "   - Nombre: {$usuario->nombre}\n";
    echo "   - Correo: {$usuario->correo}\n\n";

    echo "📤 Enviando correo de bienvenida...\n";
    
    // Crear instancia del correo
    $correo = new BienvenidaMeditech($usuario);
    
    echo "✅ Clase de correo creada exitosamente\n";
    echo "   - Asunto: " . $correo->build()->subject . "\n";
    echo "   - Vista: emails.bienvenida_meditech\n\n";

    // Simular envío (en desarrollo se capturará en Mailtrap)
    Mail::to($usuario->correo)->send($correo);
    
    echo "🎉 ¡CORREO ENVIADO EXITOSAMENTE!\n";
    echo "===============================\n";
    echo "El correo se ha enviado a Mailtrap.\n";
    echo "Revisa tu bandeja de Mailtrap para ver el correo.\n\n";
    
    echo "📋 CONFIGURACIÓN ACTUAL:\n";
    echo "- MAIL_MAILER: " . config('mail.default') . "\n";
    echo "- MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "- MAIL_FROM: " . config('mail.from.address') . "\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR AL ENVIAR CORREO:\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n✅ PRUEBA COMPLETADA\n";
