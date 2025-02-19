<?php

namespace App\Models\Nota;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNotaTecnicaCritico extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'conteudo',
        'user_id',
        'observacao',
        'observacao_user_id',
    ];

    public function notasTecnicas()
    {
        return $this->belongsTo(EsialNotaTecnica::class, 'esial_nota_tecnica_id');
    }

    public function getCreatedAtAttribute()
    {
        return is_null($this->attributes['created_at']) ? null : Carbon::parse($this->attributes['created_at'])->format('d/m/Y H:i:s');
    }
}
