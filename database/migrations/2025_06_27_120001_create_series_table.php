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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_administrativa_id')
                  ->constrained('unidades_administrativas')
                  ->onDelete('restrict')
                  ->comment('Unidad administrativa a la que pertenece la serie');
            $table->string('numero_serie', 10)->comment('Número de la serie (ej: 11)');
            $table->string('nombre', 255)->comment('Nombre de la serie (ej: PQRS)');
            $table->text('descripcion')->nullable()->comment('Descripción de la serie');
            $table->integer('dias_respuesta')->nullable()->comment('Días límite para respuesta según TRD o ley');
            $table->boolean('activa')->default(true)->comment('Estado de la serie');
            $table->timestamps();

            // Índices
            $table->index(['activa']);
            $table->index(['unidad_administrativa_id']);
            $table->unique(['unidad_administrativa_id', 'numero_serie'], 'unique_serie_por_unidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
