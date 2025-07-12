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
            // Solo intentar eliminar si la columna trd_id existe
            if (Schema::hasColumn('radicados', 'trd_id')) {
                // Verificar si existe la clave foránea antes de eliminarla
                try {
                    $table->dropForeign(['trd_id']);
                } catch (\Exception $e) {
                    // Ignorar si la clave foránea no existe
                }
                $table->dropColumn('trd_id');
            }

            // Agregar la nueva columna subserie_id solo si no existe
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
            // Eliminar la columna subserie_id
            $table->dropForeign(['subserie_id']);
            $table->dropColumn('subserie_id');

            // Restaurar la columna trd_id en caso de rollback
            $table->unsignedBigInteger('trd_id')->nullable()->after('remitente_id');
            $table->foreign('trd_id')->references('id')->on('trd')->onDelete('set null');
        });
    }
};
