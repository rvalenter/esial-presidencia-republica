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
        Schema::create('esial_relatorio_importacao_adesaos', function (Blueprint $table) {
            $table->id();
            $table->string('dsc_partido')->nullable();
            $table->string('dsc_casa')->nullable();
            $table->string('adesao_absoluta')->nullable();
            $table->string('adesao_relativa')->nullable();
            $table->string('adesao_media')->nullable();
            $table->string('adesao_absoluta_crucial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_adesaos');
    }
};
