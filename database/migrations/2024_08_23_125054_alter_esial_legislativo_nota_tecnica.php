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
        Schema::table('esial_nota_tecnicas', function (Blueprint $table) {
            $table->foreignId('esial_nota_tecnica_referencia_relacao_id')
                ->nullable()
                ->after('esial_nota_tecnica_referencia_id')
                ->constrained('esial_nota_tecnica_referencia_relacaos');
            $table->date('data_referencia')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esial_nota_tecnicas', function (Blueprint $table) {
            $table->dropForeign(['esial_nota_tecnica_referencia_relacao_id']);
            $table->dropColumn('esial_nota_tecnica_referencia_relacao_id');
            $table->dropColumn('data_referencia');
        });
    }
};
