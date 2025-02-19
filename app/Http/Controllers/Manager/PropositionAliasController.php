<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionExplanationResource;
use App\Http\Services\Ai\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropositionAliasController extends Controller
{
    public function __invoke(Request $request)
    {
        return new PropositionExplanationResource([
            'explanation' => Str::remove( '"', Chat::engine("Crie um alias com no máximo 4 palavras: {$request->ementa}")),
        ]);
    }
}
