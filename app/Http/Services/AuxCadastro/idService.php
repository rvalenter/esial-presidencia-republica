<?php

namespace App\Http\Services\AuxCadastro;

use App\Models\User;
use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Support\Facades\DB;

class idService
{
    public static function adjust(): void
    {
        $idUser = User::max('id');
        $idCadastro = EsialUsuarioCadastro::max('id');

        if ($idUser > $idCadastro) {
            DB::statement("SELECT setval(pg_get_serial_sequence('esial_usuario_cadastros', 'id'), $idUser);");
        }

        if ($idCadastro > $idUser) {
            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), $idCadastro);");
        }
    }
}
