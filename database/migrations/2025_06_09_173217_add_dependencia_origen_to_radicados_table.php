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
            $table->foreignId('dependencia_origen_id')
                  ->nullable()
                  ->after('dependencia_destino_id')
                  ->constrained('dependencias')
                  ->onDelete('restrict')
                  ->comment('Dependencia de origen (para radicados internos y de salida)');

            $table->index(['dependencia_origen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radicados', function (Blueprint $table) {
            $table->dropForeign(['dependencia_origen_id']);
            $table->dropIndex(['dependencia_origen_id']);
            $table->dropColumn('dependencia_origen_id');
        });
    }
};
