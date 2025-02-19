<?php
declare(strict_types=1);

namespace App\Http\Controllers\WebServices;

use App\Http\Controllers\Controller;
use App\Http\Services\Reports\GroupAppService;
use App\Http\Services\Reports\GroupService;

class Bancadas extends Controller
{
    public function store(): void
    {
        GroupService::Bancadas();
        GroupAppService::storeGroupName();
    }
}
