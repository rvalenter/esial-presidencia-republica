<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EsialUsuarioOrgaoSeeder::class,
            EsialUsuarioCadastroSeeder::class,
            EsialUserSeeder::class,
            EsialNivelAcessoSeeder::class,
            EsialGrupoAcessosSeeder::class,
            EsialUsuarioAcessoSeeder::class,
            EsialNotaTecnicaReferenciaSeeder::class,
            EsialNotaTecnicaPosicionamentos::class,
            EsialNotaTecnicaImpactoIntensidade::class,
            EsialNotaTecnicaImpactos::class,
            EsialUsuarioPredefinicaoSeeder::class,
            EsialUsuarioAgrupamentoAcessosSeeder::class,
        ]);
    }
}
