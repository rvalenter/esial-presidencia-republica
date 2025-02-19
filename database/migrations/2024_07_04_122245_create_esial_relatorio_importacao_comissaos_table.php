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
        Schema::create('esial_relatorio_importacao_comissaos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_parlamentar')->nullable();
            $table->string('situacao')->nullable();
            $table->string('sigla')->nullable();
            $table->string('dsc_comissao')->nullable();
            $table->string('cargo_lideranca')->nullable();
            $table->string('unidade_partidaria')->nullable();
            $table->string('classificacao')->nullable();
            $table->string('demais_cargos')->nullable();
            $table->string('cargo_descricao')->nullable();
            $table->string('partido')->nullable();
            $table->string('uf')->nullable();
            $table->unsignedBigInteger('codigoParlamentar')->nullable();
            $table->string('cod_cargo_hierarquia')->nullable();
            $table->string('sig_casa_comissao')->nullable();
            $table->string('indice_medio')->nullable();
            $table->unsignedBigInteger('codigo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_comissaos');
    }
};
