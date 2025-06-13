<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClinicaIdToCitasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (!Schema::hasColumn('citas', 'clinica_id')) {
                $table->unsignedBigInteger('clinica_id')->nullable()->after('medico_id');
                $table->foreign('clinica_id')->references('id')->on('clinicas')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'clinica_id')) {
                $table->dropForeign(['clinica_id']);
                $table->dropColumn('clinica_id');
            }
        });
    }
}
