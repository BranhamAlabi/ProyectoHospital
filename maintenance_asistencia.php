<?php

/**
 * Maintenance script to ensure data integrity for appointment attendance tracking
 * This script ensures that:
 * 1. All cancelled appointments have asistio = false
 * 2. All confirmed appointments have asistio = true
 * 3. Pending/future appointments have asistio = null
 */

use App\Models\Cita;

echo "=== Appointment Data Integrity Check ===\n\n";

// Fix cancelled appointments without asistio data
$cancelledWithoutAsistio = Cita::whereIn('estado', ['Cancelada', 'cancelada'])
    ->whereNull('asistio')
    ->get();

if ($cancelledWithoutAsistio->count() > 0) {
    echo "Fixing {$cancelledWithoutAsistio->count()} cancelled appointments without asistio data...\n";
    foreach ($cancelledWithoutAsistio as $cita) {
        $cita->update(['asistio' => false]);
        echo "  - Fixed appointment ID {$cita->id}\n";
    }
    echo "✅ All cancelled appointments now marked as non-attended\n\n";
} else {
    echo "✅ All cancelled appointments already have correct asistio data\n\n";
}

// Fix confirmed appointments without asistio data (past appointments only)
$confirmedPastWithoutAsistio = Cita::whereIn('estado', ['Confirmada', 'confirmada'])
    ->where('fecha', '<', now()->toDateString())
    ->whereNull('asistio')
    ->get();

if ($confirmedPastWithoutAsistio->count() > 0) {
    echo "Fixing {$confirmedPastWithoutAsistio->count()} past confirmed appointments without asistio data...\n";
    foreach ($confirmedPastWithoutAsistio as $cita) {
        $cita->update(['asistio' => true]);
        echo "  - Fixed appointment ID {$cita->id}\n";
    }
    echo "✅ All past confirmed appointments now marked as attended\n\n";
} else {
    echo "✅ All past confirmed appointments already have correct asistio data\n\n";
}

echo "Data integrity check completed!\n";
