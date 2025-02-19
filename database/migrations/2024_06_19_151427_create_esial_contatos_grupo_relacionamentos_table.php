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
        Schema::create('esial_contatos_grupo_relacionamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_contatos_grupo_id')
                ->constrained('esial_contatos_grupos');
            $table->foreignId('esial_contato_id')
                ->constrained('esial_contatos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_contatos_grupo_relacionamentos');
    }
};
