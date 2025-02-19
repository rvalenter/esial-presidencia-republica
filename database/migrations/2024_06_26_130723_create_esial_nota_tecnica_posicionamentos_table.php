<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esial_nota_tecnica_posicionamentos', function (Blueprint $table) {
            $table->id();
            $table->string('posicionamento');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esial_nota_tecnica_posicionamentos');
    }
};
