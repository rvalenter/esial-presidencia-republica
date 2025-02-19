<?php

namespace App\Models;

use App\Models\Usuarios\EsialUsuarioAcesso;
use App\Models\Usuarios\EsialUsuarioCadastro;
use App\Models\Usuarios\EsialUsuarioCargo;
use App\Models\Usuarios\EsialUsuarioNotificacao;
use App\Models\Usuarios\EsialUsuarioOrgao;
use App\Models\Usuarios\EsialUsuarioSetor;
use App\Models\Usuarios\EsialUsuarioTipoAcesso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'cpf',
        'esial_usuario_cadastro_id',
    ];

    public $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function photo(): HasMany
    {
        return $this->hasMany(UserPhoto::class)->latest();
    }

    public function DadosCadastrais(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioCadastro::class, 'esial_usuario_cadastro_id');
    }

    public function DadosCadastraisMany(): HasMany
    {
        return $this->hasMany(EsialUsuarioCadastro::class, 'id', 'esial_usuario_cadastro_id');
    }

    public function Cargo(): HasManyThrough
    {
        return $this->hasOneThrough(
            EsialUsuarioCargo::class,
            EsialUsuarioCadastro::class,
            'esial_usuario_orgao_id',
            'id',
            'id',
            'id'
        );
    }

    public function Orgao(): hasOneThrough
    {
        return $this->hasOneThrough(
            EsialUsuarioOrgao::class,
            EsialUsuarioCadastro::class,
            'esial_usuario_orgao_id',
            'esial_usuario_cadastro_id',
            'id',
            'id',
        );
    }

    public function Setor(): HasManyThrough
    {
        return $this->hasOneThrough(
            EsialUsuarioSetor::class,
            EsialUsuarioCadastro::class,
            'esial_usuario_orgao_id',
            'id',
            'id',
            'id'
        );
    }

    public function access(): HasMany
    {
        return $this->hasMany(
            EsialUsuarioAcesso::class,
            'esial_usuario_cadastro_id',
            'esial_usuario_cadastro_id'
        );
    }

    public function notificacoes(): HasMany
    {
        return $this->hasMany(EsialUsuarioNotificacao::class);
    }

    public function hasPermission(array $permissions): bool
    {
        foreach ($this->access as $role) {
            if (in_array($role->esial_nivel_acesso_id, $permissions)) {
                return true;
            }
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasPermission([3]);
    }

    public function acessoTipo()
    {
        return $this->hasOne(EsialUsuarioTipoAcesso::class, 'user_id');
    }
}
