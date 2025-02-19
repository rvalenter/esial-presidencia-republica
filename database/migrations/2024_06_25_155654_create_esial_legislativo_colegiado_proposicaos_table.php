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
        Schema::create('esial_legislativo_colegiado_proposicaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_colegiado_id')->constrained('esial_legislativo_colegiados');
            $table->foreignId('esial_legislativo_proposicao_id')->constrained('esial_legislativo_proposicaos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_colegiado_proposicaos');
    }
};
