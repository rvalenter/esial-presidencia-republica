<?php

namespace App\Http\Services\AuxCadastro;

use App\Models\Usuarios\EsialUsuarioCargo;
use App\Models\Usuarios\EsialUsuarioOrgao;
use App\Models\Usuarios\EsialUsuarioSetor;

class FuncionalService
{
    public static function check(string $arg, string $type): void
    {
        switch ($type) {
            case 'orgao':
                $orgao = EsialUsuarioOrgao::where('nome_orgao', $arg)->first();

                if (blank($orgao)) {
                    EsialUsuarioOrgao::create([
                        'nome_orgao' => $arg,
                    ]);
                }
                break;

            case 'setor':
                $setor = EsialUsuarioSetor::where('nome_setor', $arg)->first();

                if (blank($setor)) {
                    EsialUsuarioSetor::create([
                        'nome_setor' => $arg,
                    ]);
                }
                break;

            case 'cargo':
                $cargo = EsialUsuarioCargo::where('nome_cargo', $arg)->first();

                if (blank($cargo)) {
                    EsialUsuarioCargo::create([
                        'nome_cargo' => $arg,
                    ]);
                }
                break;
        }
    }
}
