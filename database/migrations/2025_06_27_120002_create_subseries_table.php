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
        Schema::create('subseries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')
                  ->constrained('series')
                  ->onDelete('restrict')
                  ->comment('Serie a la que pertenece la subserie');
            $table->string('numero_subserie', 10)->comment('Número de la subserie (ej: 1)');
            $table->string('nombre', 255)->comment('Nombre de la subserie (ej: Petición)');
            $table->text('descripcion')->nullable()->comment('Descripción de la subserie');
            $table->integer('dias_respuesta')->nullable()->comment('Días límite para respuesta específicos de la subserie');
            $table->boolean('activa')->default(true)->comment('Estado de la subserie');
            $table->timestamps();

            // Índices
            $table->index(['activa']);
            $table->index(['serie_id']);
            $table->unique(['serie_id', 'numero_subserie'], 'unique_subserie_por_serie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subseries');
    }
};
