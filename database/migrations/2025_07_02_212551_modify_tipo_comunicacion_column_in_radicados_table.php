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
            // Cambiar la columna tipo_comunicacion de enum a string para aceptar códigos de tipos_solicitud
            $table->string('tipo_comunicacion', 50)->change()->comment('Código del tipo de comunicación desde tipos_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radicados', function (Blueprint $table) {
            // Revertir a enum original
            $table->enum('tipo_comunicacion', ['fisico', 'verbal'])->change()->comment('Tipo de comunicación');
        });
    }
};
