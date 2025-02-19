<?php

namespace App\Models\Eventos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialEventoUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_evento_id',
        'user_id',
    ];

    public function event()
    {
        return $this->belongsTo(EsialEvento::class);
    }
}
