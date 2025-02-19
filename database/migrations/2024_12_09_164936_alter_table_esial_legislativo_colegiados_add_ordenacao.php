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
        Schema::table('esial_legislativo_colegiado_proposicaos', function (Blueprint $table) {
            $table->integer('ordenacao')->nullable()->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esial_legislativo_colegiados', function (Blueprint $table) {
            $table->dropColumn('ordenacao');
        });
    }
};
