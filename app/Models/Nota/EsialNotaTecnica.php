<?php

namespace App\Models\Nota;

use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\User;
use App\Models\Usuarios\EsialUsuarioOrgao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class EsialNotaTecnica extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'esial_legislativo_proposicao_id',
        'esial_usuario_orgao_id',
        'user_id',
        'esial_nota_tecnica_impacto_intensidade_id',
        'esial_nota_tecnica_impacto_tipo_id',
        'esil_nota_tecnica_referencia_id',
        'concluida',
        'urgente',
        'confidencial',
        'esial_nota_tecnica_posicionamento_id',
        'esial_nota_tecnica_referencia_id',
        'data_referencia',
        'impacto_percepcao',
        'posicao_justificativa',
        'tipo',
    ];

    protected $casts = [
        'concluida' => 'boolean',
    ];

    public function posicionamentos()
    {
        return $this->hasMany(EsialNotaTecnicaPosicionamento::class, 'esial_nota_tecnica_id');
    }

    public function posicionamento()
    {
        return $this->belongsTo(EsialNotaTecnicaPosicionamento::class, 'esial_nota_tecnica_posicionamento_id');
    }

    public function impactoIntensidade()
    {
        return $this->belongsTo(EsialNotaTecnicaImpactoIntensidade::class, 'esial_nota_tecnica_impacto_intensidade_id');
    }

    public function impactoTipo()
    {
        return $this->belongsTo(EsialNotaTecnicaImpactoTipo::class, 'esial_nota_tecnica_impacto_tipo_id');
    }

    public function analises()
    {
        return $this->hasMany(EsialNotaTecnicaAnalise::class, 'esial_nota_tecnica_id');
    }

    public function criticos()
    {
        return $this->hasMany(EsialNotaTecnicaCritico::class, 'esial_nota_tecnica_id');
    }

    public function meritos()
    {
        return $this->hasMany(EsialNotaTecnicaMerito::class, 'esial_nota_tecnica_id');
    }

    public function conclusoes()
    {
        return $this->hasMany(EsialNotaTecnicaConclusao::class, 'esial_nota_tecnica_id');
    }

    public function anexos()
    {
        return $this->hasMany(EsialNotaTecnicaAnexo::class, 'esial_nota_tecnica_id');
    }

    public function proposicoes()
    {
        return $this->hasOne(EsialLegislativoProposicao::class, 'id', 'esial_legislativo_proposicao_id');
    }

    public function referencias()
    {
        return $this->belongsTo(EsilNotaTecnicaReferencia::class, 'esil_nota_tecnica_referencia_id');
    }

    public function usuariosOrgaos()
    {
        return $this->belongsTo(EsialUsuarioOrgao::class, 'esial_usuario_orgao_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posicao()
    {
        return $this->belongsTo(EsialNotaTecnicaPosicionamento::class, 'esial_nota_tecnica_posicionamento_id');
    }

    public function orgao()
    {
        return $this->belongsTo(EsialUsuarioOrgao::class, 'esial_usuario_orgao_id');
    }

    public function aprovacoes()
    {
        return $this->hasMany(EsialNotaTecnicaAprovacao::class, 'esial_nota_tecnica_id');
    }

    public function referenciaRelacao()
    {
        return $this->belongsToMany(
            EsialNotaTecnicaReferenciaRelacao::class,
            'esial_nota_tecnica_referencia_relacaos',
            'esial_nota_tecnica_id',
            'esial_nota_tecnica_referencia_preencimento_id'
        );
    }

    public function referenciaRelacaoMany()
    {
        return $this->hasMany(EsialNotaTecnicaReferenciaRelacao::class, 'esial_nota_tecnica_id');
    }

    public function referenciaRelacaoTable()
    {
        return $this->hasMany(EsialNotaTecnicaReferenciaRelacao::class);
    }

    public function referenciaRelacaoCompleto()
    {
        return $this->hasManyThrough(
            EsilNotaTecnicaReferencia::class,
            EsialNotaTecnicaReferenciaRelacao::class,
            'esial_nota_tecnica_id',
            'id',
            'id',
            'esil_nota_tecnica_referencia_id'
        );
    }

    public function area()
    {
        return $this->hasManyThrough(
            EsialNotaTecnicaAreaTematica::class,
            EsialNotaTecnicaAreaTematicaRelacionamento::class,
            'esial_nota_tecnica_id',
            'id',
            'id',
            'esial_nota_tecnica_area_tematica_id'
        );
    }
}
