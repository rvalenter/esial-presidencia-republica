<?php

namespace App\Models\Nota;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EsialNotaTecnicaAreaTematicaRelacao extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'esial_nota_tecnica_id',
        'esial_nota_tecnica_area_tematica_id',
    ];
}
