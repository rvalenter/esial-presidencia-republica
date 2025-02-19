<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialUsuarioCadastroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('esial_usuario_cadastros')->insert([
            [
                'cpf' => 1220564184,
                'nome' => 'Raphael Valente',
                'responsavel' => 1,
                'email' => 'raphael.valente@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 99999-9999',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 26185844818,
                'nome' => 'Guilherme',
                'responsavel' => 1,
                'email' => 'guilherme.kerr@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 99929-1466',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 55264816115,
                'nome' => 'Célio Pacheco',
                'responsavel' => 1,
                'email' => 'celio.oliveira@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 98300-9882',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 4539483161,
                'nome' => 'Thais Gabriele',
                'responsavel' => 3,
                'email' => 'thais.santos@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 99536-8350',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 82872180125,
                'nome' => 'Flávia Martins',
                'responsavel' => 3,
                'email' => 'flavia.silva@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 98111-2856',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 53115236115,
                'nome' => 'Kleyferson',
                'responsavel' => 1,
                'email' => 'kleyferson.araujo@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 99999-9991',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 26716644865,
                'nome' => 'Andrey Aurelio De Souza Correa',
                'responsavel' => 1,
                'email' => 'andrey.correa@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 98177-1647',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'cpf' => 00324544146,
                'nome' => 'Elton Gontijo',
                'responsavel' => 1,
                'email' => 'elton.gontijo@presidencia.gov.br',
                'esial_usuario_orgao_id' => 1,
                'telefone' => '(61) 99622-4221',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
