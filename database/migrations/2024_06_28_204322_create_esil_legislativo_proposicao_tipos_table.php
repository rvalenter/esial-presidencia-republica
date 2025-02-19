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
        Schema::create('esil_legislativo_proposicao_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
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
        Schema::dropIfExists('esil_legislativo_proposicao_tipos');
    }
};
