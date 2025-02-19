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
        Schema::create('esial_nota_tecnica_referencia_relacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_nota_tecnica_id')
                ->constrained('esial_nota_tecnicas');
            $table->foreignId('esial_nota_tecnica_referencia_preencimento_id')
                ->constrained('esial_nota_tecnica_referencia_preencimentos');
            $table->string('referencia')->nullable();
            $table->string('complemento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_nota_tecnica_referencia_relacaos');
    }
};
