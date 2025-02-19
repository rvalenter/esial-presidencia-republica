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
        Schema::create('esial_nota_tecnica_meritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_nota_tecnica_id')->constrained('esial_nota_tecnicas');
            $table->text('conteudo');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esia_nota_tecnica_meritos');
    }
};
