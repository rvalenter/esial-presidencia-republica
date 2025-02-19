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
        Schema::create('esial_legislativo_colegiado_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_colegiado_id')
                ->constrained('esial_legislativo_colegiados');
            $table->foreignId('esial_contato_id')
                ->nullable()
                ->constrained('esial_contatos');
            $table->string('cargo')->nullable();
            $table->string('cargo_tipo')->nullable();
            $table->string('codigo_parlamentar')->nullable();
            $table->string('alinhamento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_colegiado_participantes');
    }
};
