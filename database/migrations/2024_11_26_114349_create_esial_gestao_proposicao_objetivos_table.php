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
        Schema::create('esial_gestao_proposicao_objetivos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('esial_gestao_proposicao_geral_id')
                ->constrained('esial_gestao_proposicao_gerals');
            $table->text('objetivo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_gestao_proposicao_objetivos');
    }
};
