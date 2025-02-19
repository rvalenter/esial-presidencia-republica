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
        Schema::create('esial_nota_tecnica_analises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_nota_tecnica_id')
                ->constrained('esial_nota_tecnicas');
            $table->foreignId('user_id')
                ->constrained('users');
            $table->foreignId('esial_nota_tecnica_impacto_tipo_id')
                ->nullable()
                ->constrained('esial_nota_tecnica_impacto_tipos');
            $table->foreignId('esial_nota_tecnica_impacto_intensidade_id')
                ->nullable()
                ->constrained('esial_nota_tecnica_impacto_intensidades');
            $table->foreignId('esial_nota_tecnica_area_tematica_id')
                ->nullable()
                ->constrained('esial_nota_tecnica_area_tematicas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esia_nota_tecnica_analises');
    }
};
