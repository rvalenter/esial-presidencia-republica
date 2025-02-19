<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialNotaTecnicaReferenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_nota_tecnica_referencia_preencimentos')->insert([
            [
                'referencia' => 'Texto Original',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Substitutivo da Câmara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Substitutivo do Senado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Substitutivo de Comissão',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Substitutivo de Plenário',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Substitutivo de Parlamentar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Emendas de Comissão',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Emendas de Parlamentares',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Emendas de Plenário',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Migração',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Emandas da Camara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'referencia' => 'Emandas do Senado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('esil_nota_tecnica_referencias')->insert([
            [
                'referencia' => uuid_create(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
