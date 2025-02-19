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
        Schema::create('esial_agrupamento_acessos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_grupo_acesso_id')->constrained('esial_grupo_acessos');
            $table->foreignId('esial_nivel_acesso_id')->constrained('esial_nivel_acessos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_agrupamento_acessos');
    }
};
