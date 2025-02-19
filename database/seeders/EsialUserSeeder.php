<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'cpf' => 1220564184,
                'name' => 'Raphael Valente',
                'esial_usuario_cadastro_id' => 1,
                'email' => 'raphael.valente@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 26185844818,
                'name' => 'Guilherme',
                'esial_usuario_cadastro_id' => 2,
                'email' => 'guilherme.kerr@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 55264816115,
                'name' => 'Célio Pacheco',
                'esial_usuario_cadastro_id' => 3,
                'email' => 'celio.oliveira@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 4539483161,
                'name' => 'Thais Gabriele',
                'esial_usuario_cadastro_id' => 4,
                'email' => 'thais.santos@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 82872180125,
                'name' => 'Flávia Martins',
                'esial_usuario_cadastro_id' => 5,
                'email' => 'flavia.silva@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 53115236115,
                'name' => 'Kleyferson Araujo',
                'esial_usuario_cadastro_id' => 1,
                'email' => 'kleyferson.araujo@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 26716644865,
                'name' => 'Andrey Aurelio De Souza Correa',
                'esial_usuario_cadastro_id' => 1,
                'email' => 'andrey.correa@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ], [
                'cpf' => 00324544146,
                'name' => 'Elton Gontijo',
                'esial_usuario_cadastro_id' => 1,
                'email' => 'elton.gontijo@presidencia.gov.br',
                'password' => bcrypt(''),
                'email_verified_at' => now(),
            ],
        ]);
    }
}
