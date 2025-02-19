<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialAgrupamentoAcesso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_grupo_acesso_id',
        'esial_nivel_acesso_id',
    ];
}
