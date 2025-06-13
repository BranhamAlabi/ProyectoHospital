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
        Schema::create('medico_clinica', function (Blueprint $table) {
            $table->unsignedBigInteger('medico_id');
            $table->unsignedBigInteger('clinica_id');
            
            $table->primary(['medico_id', 'clinica_id']);
            
            $table->foreign('medico_id')
                  ->references('id')
                  ->on('medicos')
                  ->onDelete('cascade');
                  
            $table->foreign('clinica_id')
                  ->references('id')
                  ->on('clinicas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medico_clinica');
    }
};
