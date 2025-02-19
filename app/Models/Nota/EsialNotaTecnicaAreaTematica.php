<?php

namespace App\Models\Nota;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EsialNotaTecnicaAreaTematica extends Model
{
    protected $fillable = [
        'area_tematica',
    ];

    public function notaTecnica()
    {
        return $this->hasManyThrough(
            EsialNotaTecnica::class,
            EsialNotaTecnicaAreaTematicaRelacionamento::class,
            'esial_nota_tecnica_area_tematica_id',
            'id',
            'id',
            'esial_nota_tecnica_id'
        );
    }

    public function user()
    {
        return $this->hasManyThrough(
            User::class,
            EsialNotaTecnicaAreaTematicaRelacionamento::class,
            'esial_nota_tecnica_area_tematica_id',
            'id',
            'id',
            'user_id'
        );
    }
}
