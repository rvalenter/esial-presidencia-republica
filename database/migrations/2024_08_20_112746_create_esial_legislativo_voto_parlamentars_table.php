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
        Schema::create('esial_legislativo_voto_parlamentars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_voto_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('esial_legislativo_parlamentar_id');
            $table->string('voto');
            $table->integer('peso');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_voto_parlamentars');
    }
};
