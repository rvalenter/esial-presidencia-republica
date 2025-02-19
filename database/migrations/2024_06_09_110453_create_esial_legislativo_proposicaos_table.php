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
        Schema::create('esial_legislativo_proposicaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_externo')->unique();
            $table->string('sigla');
            $table->string('numero');
            $table->integer('ano');
            $table->enum('origem', OrigemEnum::toArray());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_proposicaos');
    }
};
