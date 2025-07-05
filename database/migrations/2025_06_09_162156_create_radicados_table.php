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
        Schema::create('radicados', function (Blueprint $table) {
            $table->id();
            $table->string('numero_radicado', 50)->unique()->comment('Número único de radicado');
            $table->enum('tipo', ['entrada', 'interno', 'salida'])->comment('Tipo de radicado');
            $table->date('fecha_radicado')->comment('Fecha de radicación');
            $table->time('hora_radicado')->comment('Hora de radicación');

            // Relaciones
            $table->foreignId('remitente_id')->constrained('remitentes')->onDelete('cascade');
            $table->foreignId('dependencia_destino_id')->constrained('dependencias')->onDelete('restrict');
            $table->foreignId('usuario_radica_id')->constrained('users')->onDelete('restrict');

            // Información del documento
            $table->enum('medio_recepcion', ['fisico', 'email', 'web', 'telefono', 'fax', 'otro'])
                  ->comment('Medio por el cual se recibió');
            $table->enum('tipo_comunicacion', ['fisico', 'verbal'])->comment('Tipo de comunicación');
            $table->integer('numero_folios')->default(1)->comment('Número de folios del documento');
            $table->text('observaciones')->nullable()->comment('Observaciones del radicado');

            // Destino y respuesta
            $table->enum('medio_respuesta', ['fisico', 'email', 'telefono', 'presencial', 'no_requiere'])
                  ->default('no_requiere')->comment('Medio de respuesta solicitado');
            $table->enum('tipo_anexo', ['original', 'copia', 'ninguno'])->default('ninguno')
                  ->comment('Tipo de anexo');
            $table->date('fecha_limite_respuesta')->nullable()->comment('Fecha límite para respuesta');

            // Estado y seguimiento
            $table->enum('estado', ['pendiente', 'en_proceso', 'respondido', 'archivado'])
                  ->default('pendiente')->comment('Estado actual del radicado');
            $table->text('respuesta')->nullable()->comment('Respuesta dada al radicado');
            $table->date('fecha_respuesta')->nullable()->comment('Fecha de respuesta');
            $table->foreignId('usuario_responde_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Índices
            $table->index(['numero_radicado']);
            $table->index(['tipo']);
            $table->index(['fecha_radicado']);
            $table->index(['estado']);
            $table->index(['dependencia_destino_id']);
            $table->index(['fecha_limite_respuesta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicados');
    }
};
