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
            // Verificar si la columna no existe antes de agregarla
            if (!Schema::hasColumn('radicados', 'subserie_id')) {
                $table->foreignId('subserie_id')
                      ->nullable()
                      ->after('remitente_id')
                      ->constrained('subseries')
                      ->onDelete('restrict')
                      ->comment('Subserie del TRD jerárquico');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radicados', function (Blueprint $table) {
            // Verificar si la columna existe antes de eliminarla
            if (Schema::hasColumn('radicados', 'subserie_id')) {
                $table->dropForeign(['subserie_id']);
                $table->dropColumn('subserie_id');
            }
        });
    }
};
