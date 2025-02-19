<?php

namespace App\Models\Eventos;

use App\Models\Legislativo\EsialLegislativoColegiadoReuniao;
use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialEvento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(EsialEventoUser::class, 'esial_evento_users', 'esial_evento_id', 'user_id');
    }

    public function propositions()
    {
        return $this->belongsToMany(EsialEventoProposicao::class, 'esial_evento_proposicaos', 'esial_evento_id', 'esial_legislativo_proposicao_id');
    }

    public function colegiados()
    {
        return $this->belongsToMany(EsialEventoColegiado::class, 'esial_evento_colegiados', 'esial_evento_id', 'esial_legislativo_colegiado_id');
    }

    public function guest()
    {
        return $this->hasMany(EsialEventoUser::class);
    }

    public function guestData()
    {
        return $this->hasManyThrough(
            User::class,
            EsialEventoUser::class,
            'esial_evento_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function propositionsData()
    {
        return $this->hasManyThrough(
            EsialLegislativoProposicao::class,
            EsialEventoProposicao::class,
            'esial_evento_id',
            'id',
            'id',
            'esial_legislativo_proposicao_id'
        );
    }

    public function colegiadosData()
    {
        return $this->hasOneThrough(
            EsialLegislativoColegiadoReuniao::class,
            EsialEventoColegiado::class,
            'esial_evento_id',
            'id',
            'id',
            'esial_legislativo_colegiado_id'
        );
    }
}
