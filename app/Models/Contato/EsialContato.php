<?php

namespace App\Models\Contato;

use App\Models\Legislativo\EsialLegislativoParlamentar;
use App\Models\Relatorio\EsialRelatorioImportacaoBaseParlamentar;
use App\Models\Relatorio\EsialRelatorioImportacaoVotacao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class EsialContato extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'nome',
        'cargo',
        'organizacao',
        'telefone',
        'celular',
        'email',
        'endereco',
        'observacoes',
        'user_id',
    ];

    public function toSearchableArray(): array
    {
        return [
            'nome' => $this->nome,
            'cargo' => $this->cargo,
        ];
    }

    public function relacionamentos()
    {
        return $this->belongsToMany(EsialContatosRelacionamento::class, 'esial_contatos_relacionamentos', 'esial_contato_id', 'contato');
    }

    public function foto()
    {
        return $this->belongsToMany(EsialContatosFoto::class, 'esial_contatos_fotos', 'esial_contato_id', 'foto');
    }

    public function parlamentar()
    {
        return $this->belongsToMany(EsialLegislativoParlamentar::class, 'esial_legislativo_parlamentars', 'esial_contato_id', 'codigo');
    }

    public function getParlamentar()
    {
        return $this->hasOne(EsialLegislativoParlamentar::class);
    }

    public function parlamentarDados()
    {
        return $this->hasOneThrough(
            EsialRelatorioImportacaoBaseParlamentar::class,
            EsialLegislativoParlamentar::class,
            'esial_contato_id',
            'cod_parlamentar',
            'id',
            'codigo'
        );
    }

    public function parlamentarVotacoes()
    {
        return $this->hasOneThrough(
            EsialRelatorioImportacaoVotacao::class,
            EsialLegislativoParlamentar::class,
            'esial_contato_id',
            'cod_parlamentar',
            'id',
            'codigo'
        );
    }

    public function getPhoto()
    {
        return $this->hasOne(EsialContatosFoto::class);
    }

    public function photoLink()
    {
        return $this->hasOneThrough(
            EsialRelatorioImportacaoBaseParlamentar::class,
            EsialLegislativoParlamentar::class,
            'esial_contato_id',
            'cod_parlamentar',
            'id',
            'codigo'
        )->select('url_foto_x');
    }

    public function partido()
    {
        return $this->hasOneThrough(
            EsialRelatorioImportacaoBaseParlamentar::class,
            EsialLegislativoParlamentar::class,
            'esial_contato_id',
            'cod_parlamentar',
            'id',
            'codigo'
        )->select('siglaPartidoUf');
    }

    public function getRelationships()
    {
        return $this->hasManyThrough(
            EsialContato::class,
            EsialContatosRelacionamento::class,
            'esial_contato_id',
            'id',
            'id',
            'contato'
        );
    }

    public function Bancadas()
    {
        return $this->hasManyThrough(
            EsialContatosGrupo::class,
            EsialContatosGrupoRelacionamento::class,
            'esial_contato_id',
            'id',
            'id',
            'esial_contatos_grupo_id'
        );
    }
}
