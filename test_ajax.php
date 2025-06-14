<?php
// Simple test script to check AJAX endpoint
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\PacienteCitasController;
use App\Models\Usuarios;

// Create Laravel app instance for testing
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Create a fake request
    $request = new Request([
        'especialidad_id' => 1,
        'clinica_id' => null
    ]);
    
    // Create controller instance
    $controller = new PacienteCitasController();
    
    echo "Testing AJAX endpoint...\n";
    $response = $controller->getMedicosPorEspecialidad($request);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response content: " . $response->getContent() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
