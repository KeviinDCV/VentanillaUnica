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
        Schema::create('remitentes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['anonimo', 'registrado'])->comment('Tipo de remitente');
            $table->enum('tipo_documento', ['CC', 'CE', 'TI', 'PP', 'NIT', 'OTRO'])->nullable()
                  ->comment('Tipo de documento de identidad');
            $table->string('numero_documento', 20)->nullable()->comment('Número de documento');
            $table->string('nombre_completo', 255)->comment('Nombre completo del remitente');
            $table->string('telefono', 20)->nullable()->comment('Teléfono de contacto');
            $table->string('email', 255)->nullable()->comment('Email de contacto');
            $table->text('direccion')->nullable()->comment('Dirección física');
            $table->string('ciudad', 100)->nullable()->comment('Ciudad de residencia');
            $table->string('departamento', 100)->nullable()->comment('Departamento');
            $table->string('entidad', 255)->nullable()->comment('Entidad que representa (si aplica)');
            $table->text('observaciones')->nullable()->comment('Observaciones adicionales');
            $table->timestamps();

            // Índices
            $table->index(['tipo']);
            $table->index(['numero_documento']);
            $table->index(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remitentes');
    }
};
