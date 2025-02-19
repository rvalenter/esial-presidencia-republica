<?php

use App\Enums\Gestao\PrioridadePareceEnum;
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
        Schema::create('esial_gestao_solitacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_nota_tecnica_id')->constrained('esial_nota_tecnicas');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('esial_usuario_orgao_id')->constrained('esial_usuario_orgaos');
            $table->foreignId('esial_legislativo_proposicao_id')->constrained('esial_legislativo_proposicaos');
            $table->enum('prioridade', PrioridadePareceEnum::toArray())->default(PrioridadePareceEnum::BAIXO);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_gestao_solitacaos');
    }
};
