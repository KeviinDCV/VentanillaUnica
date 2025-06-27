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
        Schema::create('unidades_administrativas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique()->comment('Código único de la unidad administrativa');
            $table->string('nombre', 255)->comment('Nombre de la unidad administrativa');
            $table->text('descripcion')->nullable()->comment('Descripción de la unidad administrativa');
            $table->boolean('activa')->default(true)->comment('Estado de la unidad administrativa');
            $table->timestamps();

            // Índices
            $table->index(['activa']);
            $table->index(['codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_administrativas');
    }
};
