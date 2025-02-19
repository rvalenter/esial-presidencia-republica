<?php
declare(strict_types=1);

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contato\RelacionametoResource;
use App\Models\Contato\EsialContato;

class RelacionametoController extends Controller
{
    public function show(EsialContato $esialContato): RelacionametoResource
    {
        return new RelacionametoResource($esialContato->load(['getPhoto', 'getRelationships.getPhoto']));
    }
}
