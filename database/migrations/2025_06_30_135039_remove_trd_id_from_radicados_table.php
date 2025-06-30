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
            // Eliminar la columna trd_id ya que se migra al sistema jerárquico
            $table->dropForeign(['trd_id']);
            $table->dropColumn('trd_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radicados', function (Blueprint $table) {
            // Restaurar la columna trd_id en caso de rollback
            $table->unsignedBigInteger('trd_id')->nullable()->after('remitente_id');
            $table->foreign('trd_id')->references('id')->on('trd')->onDelete('set null');
        });
    }
};
