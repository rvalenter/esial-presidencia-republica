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
        Schema::create('esial_relatorio_importacao_base_parlamentars', function (Blueprint $table) {
            $table->id();
            $table->string('nomeParlamentar')->nullable();
            $table->string('siglaPartido')->nullable();
            $table->string('uf')->nullable();
            $table->string('tel_gabinete')->nullable();
            $table->string('tel_particular')->nullable();
            $table->string('tel_chefe_gab')->nullable();
            $table->string('chefe_gab')->nullable();
            $table->string('tel_pessoal')->nullable();
            $table->string('cod_bloco')->nullable();
            $table->string('alinhamento')->nullable();
            $table->string('avaliacao')->nullable();
            $table->string('absoluta')->nullable();
            $table->string('relativa')->nullable();
            $table->string('ausencia')->nullable();
            $table->string('cargo_lideranca_y')->nullable();
            $table->string('siglaPartidoUf')->nullable();
            $table->string('parlamentar_completo_x')->nullable();
            $table->string('url_foto_x')->nullable();
            $table->string('email_x')->nullable();
            $table->string('avaliacao_path')->nullable();
            $table->unsignedBigInteger('cod_parlamentar')->nullable();
            $table->string('percentual_pesos')->nullable();
            $table->string('obstrucao')->nullable();
            $table->string('abstencao')->nullable();
            $table->string('presidente')->nullable();
            $table->string('contra')->nullable();
            $table->string('a_favor')->nullable();
            $table->string('total')->nullable();
            $table->string('ausencia_simples')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_base_parlamentars');
    }
};
