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
        Schema::table('trd', function (Blueprint $table) {
            // Verificar si la columna no existe antes de agregarla
            if (!Schema::hasColumn('trd', 'dias_respuesta')) {
                $table->integer('dias_respuesta')->nullable()->after('disposicion_final')->comment('Días límite para respuesta según TRD o ley');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trd', function (Blueprint $table) {
            if (Schema::hasColumn('trd', 'dias_respuesta')) {
                $table->dropColumn('dias_respuesta');
            }
        });
    }
};
