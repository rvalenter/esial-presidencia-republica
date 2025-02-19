<?php
declare(strict_types=1);

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioCadastro extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'responsavel',
        'email',
        'telefone',
        'esial_usuario_cargo_id',
        'esial_usuario_orgao_id',
        'esial_usuario_setor_id',
    ];

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioCargo::class, 'esial_usuario_cargo_id');
    }

    public function orgao(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioOrgao::class, 'esial_usuario_orgao_id');
    }

    public function orgaos(): HasMany
    {
        return $this->hasMany(EsialUsuarioOrgao::class, 'id', 'esial_usuario_orgao_id');
    }

    public function setor(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioSetor::class, 'esial_usuario_setor_id');
    }

    public function acessos(): HasMany
    {
        return $this->hasMany(EsialUsuarioAcesso::class, 'esial_usuario_cadastro_id');
    }

    public function acessoNome(): HasManyThrough
    {
        return $this->hasManyThrough(
            EsialNivelAcesso::class,
            EsialUsuarioAcesso::class,
            'esial_usuario_cadastro_id',
            'id',
            'id',
            'esial_nivel_acesso_id'
        );
    }

    public function acessoGrupo(): HasOneThrough
    {
        return $this->hasOneThrough(
            EsialGrupoAcesso::class,
            EsialUsuarioPredefinicao::class,
            'esial_usuario_cadastro_id',
            'id',
            'id',
            'esial_grupo_acesso_id'
        );
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function getFotoAttribute(): UserPhoto
    {
        return $this->fotos->last();
    }

    public function Users(): HasMany
    {
        return $this->hasMany(EsialUsuarioCadastro::class, 'responsavel');
    }

    public function MyUsers(): HasMany
    {
        return $this
            ->hasMany(EsialUsuarioCadastro::class, 'esial_usuario_orgao_id', 'esial_usuario_orgao_id')
            ->whereHas('acessos', function ($query) {
                $query->whereIn('esial_nivel_acesso_id', [4, 9]);
            });
    }

    public function Descricoes(): HasMany
    {
        return $this->hasMany(EsialUsuarioDescricao::class, 'esial_usuario_cadastro_id');
    }

    public function Assessor(): HasMany
    {
        return $this->hasMany(EsialUsuariosAssessor::class);
    }
}
