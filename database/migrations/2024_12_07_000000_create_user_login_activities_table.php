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
        Schema::create('user_login_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->boolean('remember_me')->default(false);
            $table->string('session_id')->nullable();
            $table->timestamp('login_at');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['user_id', 'login_at']);
            $table->index('login_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_activities');
    }
};
