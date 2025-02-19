<?php

namespace Tests\Http\Controllers\Manager;

use App\Http\Controllers\Manager\PropositionController;
use PHPUnit\Framework\TestCase;

class PropositionControllerTest extends TestCase
{

    public function testIndex()
    {
        $controller = new PropositionController();
        $response = $controller->index();
        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }
}
