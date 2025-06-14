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
        Schema::create('paciente_expediente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_paciente');
            $table->string('nombre_completo');
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->text('direccion');
            $table->string('telefono', 20);
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('cirugias_previas')->nullable();
            $table->text('alergias')->nullable();
            $table->text('tratamientos_actuales')->nullable();
            $table->unsignedBigInteger('editado_por')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_paciente')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('editado_por')->references('id')->on('medicos')->onDelete('set null');
            
            // Index para optimizar búsquedas
            $table->index('id_paciente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente_expediente');
    }
};
