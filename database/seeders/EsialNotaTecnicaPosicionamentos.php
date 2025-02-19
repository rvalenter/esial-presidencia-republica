<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialNotaTecnicaPosicionamentos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_nota_tecnica_posicionamentos')->insert([
            [
                'posicionamento' => 'CONTRÁRIO',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'FAVORÁVEL',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'CONTRÁRIO COM AJUSTES',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'FAVORÁVEL COM AJUSTES',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'FORA DE COMPETÊNCIA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'MATÉRIA PREJUDICADA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'NADA A OPOR',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'posicionamento' => 'PERDA DE EFICÁCIA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
