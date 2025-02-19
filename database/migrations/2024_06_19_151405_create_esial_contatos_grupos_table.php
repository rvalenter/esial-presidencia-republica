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
        Schema::create('esial_contatos_grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('responsavel');
            $table->unsignedBigInteger('id_camara')->nullable();
            $table->foreignId('esial_legislativo_proposicao_id')
                ->nullable()
                ->constrained('esial_legislativo_proposicaos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_contatos_grupos');
    }
};
