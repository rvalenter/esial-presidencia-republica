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
        Schema::create('esial_relatorio_importacao_proposicaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_externo')->nullable();
            $table->string('sigla')->nullable();
            $table->string('numero')->nullable();
            $table->string('ano')->nullable();
            $table->string('urlinteiroteor')->nullable();
            $table->text('ementa')->nullable();
            $table->text('autor')->nullable();
            $table->string('id_autor')->nullable();
            $table->string('origem')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_proposicaos');
    }
};
