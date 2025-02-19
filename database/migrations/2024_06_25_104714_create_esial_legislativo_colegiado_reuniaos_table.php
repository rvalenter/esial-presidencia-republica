<?php

use App\Enums\Legislativo\ColegiadoReuniaoEnum;
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
        Schema::create('esial_legislativo_colegiado_reuniaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('esial_legislativo_colegiado_id')->constrained('esial_legislativo_colegiados');
            $table->enum('tipo', ColegiadoReuniaoEnum::toArray());
            $table->date('data')->nullable();
            $table->string('local')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esial_legislativo_colegiado_reuniaos');
    }
};
