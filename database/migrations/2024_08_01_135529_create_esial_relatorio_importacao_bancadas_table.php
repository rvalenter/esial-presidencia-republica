<?php

use App\Enums\Legislativo\OrigemEnum;
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
        Schema::create('esial_relatorio_importacao_bancadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_parlamentar');
            $table->unsignedBigInteger('cod_parlamentar');
            $table->string('nome');
            $table->string('partido');
            $table->string('uf');
            $table->string('tipo_bancada');
            $table->enum('casa', OrigemEnum::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_relatorio_importacao_bancadas');
    }
};
