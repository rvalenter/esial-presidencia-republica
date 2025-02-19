<?php

namespace App\Http\Services\NotaTecnicaReferencia;

use App\Models\Nota\EsilNotaTecnicaReferencia;

class ReferenciaService
{
    public static function store(): int
    {
        return EsilNotaTecnicaReferencia::updateOrCreate([
            'referencia' => uuid_create(),
        ])->id;
    }
}
