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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radicado_id')->constrained('radicados')->onDelete('cascade');
            $table->string('nombre_archivo', 255)->comment('Nombre original del archivo');
            $table->string('ruta_archivo', 500)->comment('Ruta donde se almacena el archivo');
            $table->string('tipo_mime', 100)->comment('Tipo MIME del archivo');
            $table->bigInteger('tamaño_archivo')->comment('Tamaño del archivo en bytes');
            $table->string('hash_archivo', 64)->comment('Hash SHA256 del archivo para integridad');
            $table->text('descripcion')->nullable()->comment('Descripción del documento');
            $table->boolean('es_principal')->default(true)->comment('Indica si es el documento principal');
            $table->timestamps();

            // Índices
            $table->index(['radicado_id']);
            $table->index(['es_principal']);
            $table->index(['hash_archivo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
