<?php

namespace Tests\Http\Controllers\Manager;

use App\Http\Controllers\Manager\AgendaController;
use PHPUnit\Framework\TestCase;

class AgendaControllerTest extends TestCase
{

    public function testIndex()
    {
        $controller = new AgendaController();
        $response = $controller->index();
        $this->assertInstanceOf(\Inertia\Response::class, $response);
    }
}
