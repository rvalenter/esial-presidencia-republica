<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaResource;
use App\Http\Services\NotaTecnica\NotaTecnicaService;
use Illuminate\Http\Request;

class NotaTecnicaSearchController extends Controller
{
    public function __invoke(Request $request): NotaTecnicaResource
    {
        return new NotaTecnicaResource(NotaTecnicaService::cache($request));
    }
}
