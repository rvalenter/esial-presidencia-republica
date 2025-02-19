<?php
declare(strict_types=1);

namespace App\Models\Legislativo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialLegislativoColegiadoProposicao extends Model
{
    use SoftDeletes;

    public function dados(): HasOne
    {
        return $this->hasOne(EsialLegislativoColegiado::class, 'id', 'esial_legislativo_colegiado_id');
    }

    public function parecer()
    {
        return $this->hasOne(EsialLegislativoColegiadoProposicaoPerecer::class, 'esial_legislativo_colegiado_proposicao_id', 'id');
    }
}
