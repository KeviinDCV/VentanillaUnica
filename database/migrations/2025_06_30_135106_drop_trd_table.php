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
        // Eliminar completamente la tabla trd (solo si existe)
        // dropIfExists no falla si la tabla no existe
        Schema::dropIfExists('trd');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear la tabla trd en caso de rollback
        Schema::create('trd', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('serie');
            $table->string('subserie')->nullable();
            $table->text('asunto');
            $table->integer('retencion_archivo_gestion');
            $table->integer('retencion_archivo_central');
            $table->enum('disposicion_final', ['conservacion_total', 'eliminacion', 'seleccion', 'microfilmacion']);
            $table->integer('dias_respuesta')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
};
