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
        Schema::create('trd', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique()->comment('Código único TRD');
            $table->string('serie', 255)->comment('Serie documental');
            $table->string('subserie', 255)->nullable()->comment('Subserie documental');
            $table->text('asunto')->comment('Asunto o descripción del documento');
            $table->integer('retencion_archivo_gestion')->default(0)->comment('Años en archivo de gestión');
            $table->integer('retencion_archivo_central')->default(0)->comment('Años en archivo central');
            $table->enum('disposicion_final', ['conservacion_total', 'eliminacion', 'seleccion', 'microfilmacion'])
                  ->default('conservacion_total')->comment('Disposición final del documento');
            $table->integer('dias_respuesta')->nullable()->comment('Días límite para respuesta según TRD o ley');
            $table->text('observaciones')->nullable()->comment('Observaciones adicionales');
            $table->boolean('activo')->default(true)->comment('Estado del registro TRD');
            $table->timestamps();

            // Índices
            $table->index(['activo']);
            $table->index(['codigo']);
            $table->index(['serie']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trd');
    }
};
