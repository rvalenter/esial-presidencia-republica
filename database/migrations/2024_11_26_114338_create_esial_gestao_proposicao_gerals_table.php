<?php

use App\Enums\Legislativo\OrigemEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('esial_gestao_proposicao_gerals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_usuario_cadastro_id')
                ->constrained('esial_usuario_cadastros');
            $table->foreignId('esial_legislativo_proposicao_id')
                ->constrained('esial_legislativo_proposicaos');
            $table->enum('casa_atual', OrigemEnum::toArray());
            $table->string('apelido');
            $table->boolean('evento')->default(false);
            $table->boolean('prioritaria')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_gestao_proposicao_gerals');
    }
};
