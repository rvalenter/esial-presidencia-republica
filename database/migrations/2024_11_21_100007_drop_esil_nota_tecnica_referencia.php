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
        Schema::dropIfExists('esil_nota_tecnica_referencias');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('esil_nota_tecnica_referencias', function (Blueprint $table) {
            $table->id();
            $table->string('referencia');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
