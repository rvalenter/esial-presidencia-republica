<?php
declare(strict_types=1);

namespace App\Http\Controllers\WebServices;

use App\Http\Controllers\Controller;
use App\Http\Services\Reports\ParliamentaryFrontService;

class FrentesParlamentaresController extends Controller
{
    public function store(): void
    {
        ParliamentaryFrontService::Fronts();
    }
}
