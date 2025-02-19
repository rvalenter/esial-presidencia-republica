<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialUsuarioPredefinicaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_usuario_predefinicaos')->insert([
            [
                'esial_usuario_cadastro_id' => 3,
                'esial_grupo_acesso_id' => 2,
                'responsavel' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'esial_usuario_cadastro_id' => 4,
                'esial_grupo_acesso_id' => 2,
                'responsavel' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'esial_usuario_cadastro_id' => 5,
                'esial_grupo_acesso_id' => 2,
                'responsavel' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'esial_usuario_cadastro_id' => 2,
                'esial_grupo_acesso_id' => 2,
                'responsavel' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],  [
                'esial_usuario_cadastro_id' => 6,
                'esial_grupo_acesso_id' => 2,
                'responsavel' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
