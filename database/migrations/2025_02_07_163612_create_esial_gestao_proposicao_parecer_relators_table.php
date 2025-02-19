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
        Schema::create('esial_gestao_proposicao_parecer_relators', function (Blueprint $table) {
            $table->id();
            $table->integer('esial_legislativo_proposicao_id');
            $table->integer('esial_legislativo_colegiado_id');
            $table->text('parecer');
            $table->integer('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_gestao_proposicao_parecer_relators');
    }
};
