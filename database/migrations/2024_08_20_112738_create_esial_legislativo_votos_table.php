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
        Schema::create('esial_legislativo_votos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_proposicao_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('item_voto');
            $table->string('orientacao_governo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_votos');
    }
};
