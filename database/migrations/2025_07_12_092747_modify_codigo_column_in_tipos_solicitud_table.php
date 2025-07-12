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
            // Hacer que el campo codigo sea nullable (mantiene el índice único existente)
            $table->string('codigo', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_solicitud', function (Blueprint $table) {
            // Revertir el campo codigo a no nullable
            $table->string('codigo', 20)->nullable(false)->change();
        });
    }
};
