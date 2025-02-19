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
        Schema::create('esial_legislativo_colegiados', function (Blueprint $table) {
            $table->id();
            $table->string('sigla')->nullable();
            $table->text('descricao')->nullable();
            $table->string('codigo')->nullable();
            $table->enum('origem', OrigemEnum::toArray())->nullable();
            $table->string('composicao')->nullable();
            $table->string('indice')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_colegiados');
    }
};
