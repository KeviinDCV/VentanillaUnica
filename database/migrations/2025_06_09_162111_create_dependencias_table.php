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
        Schema::create('dependencias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique()->comment('Código único de la dependencia');
            $table->string('nombre', 255)->comment('Nombre completo de la dependencia');
            $table->string('sigla', 20)->nullable()->comment('Sigla o abreviatura');
            $table->text('descripcion')->nullable()->comment('Descripción de la dependencia');
            $table->string('responsable', 255)->nullable()->comment('Nombre del responsable');
            $table->string('telefono', 20)->nullable()->comment('Teléfono de contacto');
            $table->string('email', 255)->nullable()->comment('Email de contacto');
            $table->boolean('activa')->default(true)->comment('Estado de la dependencia');
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
        Schema::dropIfExists('dependencias');
    }
};
