<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMedicosTableRemoveEspId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicos', function (Blueprint $table) {
            if (Schema::hasColumn('medicos', 'esp_id')) {
                // Foreign key drop removed because it does not exist
                $table->dropColumn('esp_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->unsignedBigInteger('esp_id')->nullable();

            $table->foreign('esp_id')->references('id')->on('especialidad')->onDelete('cascade');
        });
    }
}
