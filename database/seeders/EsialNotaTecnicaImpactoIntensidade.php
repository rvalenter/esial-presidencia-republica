<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialNotaTecnicaImpactoIntensidade extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_nota_tecnica_impacto_intensidades')->insert([
            [
                'intensidade' => 'Baixo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'intensidade' => 'Moderado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'intensidade' => 'Elevado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'intensidade' => 'Nenhum Impacto',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
