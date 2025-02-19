<?php

namespace Tests\Http\Controllers\Manager;

use App\Http\Controllers\Manager\PropositionAutoCompleteController;
use PHPUnit\Framework\TestCase;

class PropositionAutoCompleteControllerTest extends TestCase
{

    public function testIndex()
    {
        $request = new \Illuminate\Http\Request();
        $request->initialize(['arg' => 'test']);
        $controller = new PropositionAutoCompleteController();
        $response = $controller->index($request);
        $this->assertInstanceOf(\App\Http\Resources\Manager\PropositionAutoCompleteResource::class, $response);
    }
}
