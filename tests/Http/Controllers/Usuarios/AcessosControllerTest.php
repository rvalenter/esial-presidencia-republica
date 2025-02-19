<?php

use App\Models\Usuarios\EsialNivelAcesso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcessosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_destroys_acesso_successfully()
    {
        $acesso = EsialNivelAcesso::factory()->create();

        $response = $this->deleteJson(route('acessos.destroy', ['id' => $acesso->id]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('esial_nivel_acessos', ['id' => $acesso->id]);
    }

    public function it_returns_404_if_acesso_not_found()
    {
        $response = $this->deleteJson(route('acessos.destroy', ['id' => 999]));

        $response->assertStatus(404);
    }
}
