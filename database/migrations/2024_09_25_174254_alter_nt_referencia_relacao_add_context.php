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
        Schema::table('esial_nota_tecnica_referencia_relacaos', function (Blueprint $table) {
            $table->string('contexto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esial_nota_tecnica_referencia_relacaos', function (Blueprint $table) {
            $table->dropColumn('contexto');
        });
    }
};
