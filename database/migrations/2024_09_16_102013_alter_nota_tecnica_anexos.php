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
        Schema::table('esial_nota_tecnica_anexos', function (Blueprint $table) {
            $table->string('nota_tecnica_sei')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esial_nota_tecnica_anexos', function (Blueprint $table) {
            $table->dropColumn('nota_tecnica_sei');
        });
    }
};
