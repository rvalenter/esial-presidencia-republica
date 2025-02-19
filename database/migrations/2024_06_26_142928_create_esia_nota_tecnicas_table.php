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
        Schema::create('esial_nota_tecnicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_proposicao_id');
            $table->foreignId('esial_usuario_orgao_id');
            $table->foreignId('user_id')
                ->nullable();
            $table->foreignId('esial_nota_tecnica_posicionamento_id')
                ->nullable();
            $table->foreignId('esial_nota_tecnica_impacto_intensidade_id')
                ->nullable();
            $table->foreignId('esial_nota_tecnica_impacto_tipo_id')
                ->nullable();
            $table->foreignId('esil_nota_tecnica_referencia_id')
                ->nullable();
            $table->boolean('confidencial')
                ->default(true);
            $table->boolean('urgente')
                ->default(false);
            $table->boolean('concluida')
                ->default(false);
            $table->string('codigo_parecer')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esia_nota_tecnicas');
    }
};
