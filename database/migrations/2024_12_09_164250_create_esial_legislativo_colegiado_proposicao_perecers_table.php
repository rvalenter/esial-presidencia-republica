<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('esial_legislativo_colegiado_proposicao_perecers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_colegiado_proposicao_id')
                ->constrained('esial_legislativo_colegiado_proposicaos')
                ->onDelete('cascade');
            $table->text('parecer')->nullable();
            $table->string('tipo_analise', 255)->nullable();
            $table->string('relator', 255)->nullable();
            $table->datetime('data_parecer')->nullable();
            $table->string('link', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_colegiado_proposicao_perecers');
    }
};
