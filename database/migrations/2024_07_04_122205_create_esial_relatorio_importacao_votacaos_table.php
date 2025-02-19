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
        Schema::create('esial_relatorio_importacao_votacaos', function (Blueprint $table) {
            $table->id();
            $table->string('dsc_votacao')->nullable();
            $table->string('data_votacao')->nullable();
            $table->string('hora_votacao')->nullable();
            $table->string('dsc_voto')->nullable();
            $table->string('dsc_orientacao_partido')->nullable();
            $table->string('dsc_orientacao_governo')->nullable();
            $table->string('dsc_peso')->nullable();
            $table->string('dsc_casa')->nullable();
            $table->string('dsc_apelido')->nullable();
            $table->unsignedBigInteger('cod_parlamentar')->nullable();
            $table->string('cod_votacao')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_votacaos');
    }
};
