<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsialNivelAcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'acesso_nome' => 'Gestão de Órgão',
                'acesso_tipo' => 'Permitirá ao usuário alterar orgão de outros usuários',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Gestão de Pessoal',
                'acesso_tipo' => 'Permite cadastrar novos usuários no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Gestão de Sistema',
                'acesso_tipo' => 'Permite criar grupos, acessos novos e importar arquivos no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Gestão de Acessos',
                'acesso_tipo' => 'Permite adicionar ou remover acessos ou grupos de acesso a um usuário',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Posicionamentos',
                'acesso_tipo' => 'Permite acessar o menu posicionamentos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Visualização de Contatos',
                'acesso_tipo' => 'Permite visualizar os contatos cadastrados no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Criar Eventos no Calendário',
                'acesso_tipo' => 'Permite ao usuário criar eventos no calenário e convidar outros usuários',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Nota Técnica - Editor',
                'acesso_tipo' => 'Permite ao usuário criar notas técnicas no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Nota Técnica - Gestor',
                'acesso_tipo' => 'Permite ao usuario revisar/ aprovar notas técnicas no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Nota Técnica - Presidência',
                'acesso_tipo' => 'Permite demandar orgãos por notas técnicas, recebe as notas técnicas concluídas para compilar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Contatos - Criar/ Editar',
                'acesso_tipo' => 'Permite ao usuário criar novos contatos ou editar existentes',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Contatos - Excluir Grupos',
                'acesso_tipo' => 'Permite ao usuário excluir um grupo de contatos existente.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Relatorios - Visualizar',
                'acesso_tipo' => 'Permite que o usuário acesse os relatorios gerenciais do sistema.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Relatorios - Importar Dados',
                'acesso_tipo' => 'Permite ao usuário importar dados para aplicação criar relatórios.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Nota Técnica - Revisor',
                'acesso_tipo' => 'Permite ao usuario revisar notas técnicas no sistema',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Dashboards - Presidencia',
                'acesso_tipo' => 'Permite ao usuario visualizar perfis da Presidencia da República',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Buscar',
                'acesso_tipo' => 'Permite ao usuario realizar buscas no app',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Todos Posicionamento - Integra',
                'acesso_tipo' => 'Permite ver os posicionamentos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Gestão Posição',
                'acesso_tipo' => 'Módulo que permite ao ASSESSOR INTERNO (SEPAR) gerir uma matéria, podendo inserir apelido, comentários restritos e publicáveis (internamente), posição do governo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'acesso_nome' => 'Gestão de Pautas Semanais',
                'acesso_tipo' => 'Órgão setorial possa gerir os itens de pauta',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ])->each(fn ($acesso) => DB::table('esial_nivel_acessos')->insert($acesso));
    }
}
