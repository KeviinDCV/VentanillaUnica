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
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->nullable(); // Código DANE opcional
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'departamento_id']); // Una ciudad por departamento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudads');
    }
};
