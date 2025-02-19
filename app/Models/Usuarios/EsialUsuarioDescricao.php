<?php

namespace App\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialUsuarioDescricao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'descricao',
    ];

    public function Cadastro(): BelongsTo
    {
        return $this->belongsTo(EsialUsuarioCadastro::class);
    }
}
