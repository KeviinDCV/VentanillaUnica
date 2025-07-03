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
        Schema::table('radicados', function (Blueprint $table) {
            $table->timestamp('fecha_finalizacion')->nullable()->after('fecha_respuesta')->comment('Fecha de finalización del proceso de radicación');
            $table->foreignId('usuario_finaliza_id')->nullable()->after('usuario_responde_id')->constrained('users')->onDelete('set null')->comment('Usuario que finaliza el radicado');
            $table->json('posicion_sello')->nullable()->after('usuario_finaliza_id')->comment('Posición del sello en el documento (x, y)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radicados', function (Blueprint $table) {
            $table->dropForeign(['usuario_finaliza_id']);
            $table->dropColumn(['fecha_finalizacion', 'usuario_finaliza_id', 'posicion_sello']);
        });
    }
};
