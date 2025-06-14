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
        Schema::table('citas', function (Blueprint $table) {
            // Agregar campo para rastrear asistencia
            $table->boolean('asistio')->nullable()->after('estado')->comment('Si el paciente asistió a la cita');
            
            // Modificar estado para incluir nuevos valores
            $table->string('estado')->default('pendiente')->change()->comment('pendiente, aprobada, cancelada');
            
            // Agregar campos para vinculación con horarios
            $table->unsignedBigInteger('doctor_schedule_id')->nullable()->after('clinica_id');
            $table->foreign('doctor_schedule_id')->references('id')->on('doctor_schedules')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['doctor_schedule_id']);
            $table->dropColumn(['asistio', 'doctor_schedule_id']);
        });
    }
};
