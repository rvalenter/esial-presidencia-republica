<?php

use App\Enums\Legislativo\ParlamentarSituacaoEnum;
use App\Enums\Legislativo\ParlamentarTipoSituacaoEnum;
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
        Schema::create('esial_legislativo_parlamentar_situacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parlamentar_id')->constrained('esial_legislativo_parlamentars');
            $table->enum('tipo_situacao', ParlamentarTipoSituacaoEnum::toArray());
            $table->enum('situacao', ParlamentarSituacaoEnum::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_parlamentar_situacaos');
    }
};
