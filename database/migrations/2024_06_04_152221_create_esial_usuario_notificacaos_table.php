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
        Schema::create('esial_usuario_notificacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->bigInteger('responsavel');
            $table->string('notificacao');
            $table->string('url')->nullable();
            $table->boolean('lida')->default(false);
            $table->boolean('prioritaria')->default(false);
            $table->timestamp('data_leitura')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_usuario_notificacaos');
    }
};
