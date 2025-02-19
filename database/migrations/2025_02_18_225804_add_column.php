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
        Schema::table('esial_legislativo_proposicao_forma_apreciacaos', function (Blueprint $table) {
            $table->int('esial_legislativo_colegiado_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esial_legislativo_proposicao_forma_apreciacaos', function (Blueprint $table) {
            $table->dropColumn('esial_legislativo_colegiado_id');
        });
    }
};
