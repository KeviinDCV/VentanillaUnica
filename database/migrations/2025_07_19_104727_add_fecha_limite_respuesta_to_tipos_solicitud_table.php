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
        Schema::table('tipos_solicitud', function (Blueprint $table) {
            $table->integer('fecha_limite_respuesta')->nullable()->after('descripcion')->comment('Días límite para responder este tipo de solicitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_solicitud', function (Blueprint $table) {
            $table->dropColumn('fecha_limite_respuesta');
        });
    }
};
