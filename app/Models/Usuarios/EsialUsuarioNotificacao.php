<?php

namespace App\Models\Usuarios;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioNotificacao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'notificacao',
        'lida',
        'prioritaria',
        'data_leitura',
        'url',
        'responsavel',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'date:d/m/Y H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cadastro()
    {
        return $this->belongsTo(EsialUsuarioCadastro::class, 'user_id', 'id');
    }
}
