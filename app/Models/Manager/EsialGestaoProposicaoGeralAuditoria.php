<?php

namespace App\Models\Manager;

use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EsialGestaoProposicaoGeralAuditoria extends Model
{
    protected $fillable = [
        "esial_legislativo_proposicao_id",
        "esial_usuario_cadastro_id",
        "casa_atual",
        "apelido",
        "prioritaria",
        "evento"
    ];

    public function usuario(): hasOne
    {
        return $this->hasOne(EsialUsuarioCadastro::class, 'id', 'esial_usuario_cadastro_id');
    }
}
