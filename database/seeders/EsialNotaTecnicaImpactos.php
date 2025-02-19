<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialNotaTecnicaImpactos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_nota_tecnica_impacto_tipos')->insert([
            [
                'tipo' => 'Nenhum Impacto',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'tipo' => 'Político',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'tipo' => 'Econômico',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'tipo' => 'Federativo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'tipo' => 'Social',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
