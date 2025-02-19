<?php

use App\Enums\Legislativo\OrigemEnum;
use App\Enums\Legislativo\ParlamentarPrioritariasEnum;
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
        Schema::create('esial_legislativo_proposicao_prioritarias', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('origem', OrigemEnum::toArray())->nullable();
            $table->enum('tipo', ParlamentarPrioritariasEnum::toArray())->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_proposicao_prioritarias');
    }
};
