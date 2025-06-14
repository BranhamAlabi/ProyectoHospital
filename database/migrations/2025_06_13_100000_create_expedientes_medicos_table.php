<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expedientes_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('usuarios');
            $table->foreignId('medico_id')->constrained('medicos');
            $table->foreignId('cita_id')->constrained('citas');
            $table->text('notas');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes_medicos');
    }
};
