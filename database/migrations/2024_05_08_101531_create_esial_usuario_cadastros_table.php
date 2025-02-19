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
        Schema::create('esial_usuario_cadastros', function (Blueprint $table) {
            $table->id();
            $table->string('nome')
                ->nullable(true);
            $table->bigInteger('cpf')
                ->unique();
            $table->string('email')
                ->unique()
                ->nullable(true);
            $table->string('telefone')
                ->nullable(true);
            $table->integer('responsavel')
                ->nullable(true);
            $table->foreignId('esial_usuario_cargo_id')
                ->nullable(true)
                ->constrained('esial_usuario_cargos');
            $table->foreignId('esial_usuario_orgao_id')
                ->nullable(true)
                ->constrained('esial_usuario_orgaos');
            $table->foreignId('esial_usuario_setor_id')
                ->nullable(true)
                ->constrained('esial_usuario_setors');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_usuario_cadastros');
    }
};
