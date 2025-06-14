<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('clinica_id')->nullable()->after('medico_id');
        });
        
        // Asignar una clínica por defecto a los registros existentes si los hay
        $firstClinica = DB::table('clinicas')->first();
        if ($firstClinica) {
            DB::table('doctor_schedules')->whereNull('clinica_id')->update(['clinica_id' => $firstClinica->id]);
        }
        
        // Ahora agregar la foreign key
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->foreign('clinica_id')->references('id')->on('clinicas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->dropForeign(['clinica_id']);
            $table->dropColumn('clinica_id');
        });
    }
};
