<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_livros()
    {
        $user = User::factory()->create();
        Livro::factory(5)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/livros');

        $response->assertStatus(200);
        $response->assertJsonStructure([[
            'id',
            'titulo',
            'usuario_publicador_id',
        ]]);
    }

    public function test_cadastrar_livro()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/livros', [
            'titulo' => 'Livro de Teste',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'titulo' => 'Livro de Teste',
        ]);

        $this->assertDatabaseHas('livros', [
            'titulo' => 'Livro de Teste',
            'usuario_publicador_id' => $user->id,
        ]);
    }
}
