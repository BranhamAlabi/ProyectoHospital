<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('medico_id');
            $table->date('fecha');
            $table->time('hora');
            $table->string('estado')->default('pendiente'); // pendiente, aprobada, desaprobada
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('paciente_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('usuarios')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
}
