<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esial_nota_tecnica_area_tematica_relacionamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_nota_tecnica_area_tematica_id');
            $table->foreignId('user_id')
                ->constrained('users');
            $table->foreignId('esial_nota_tecnica_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esial_nota_tecnica_area_tematica_relacionamentos');
    }
};
