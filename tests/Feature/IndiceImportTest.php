<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class IndiceImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importar_indices_xml() //testar importação do xml
    {
        $user = User::factory()->create();
        $livro = Livro::factory()->create(['usuario_publicador_id' => $user->id]);

        $xmlContent = '<indices>
            <indice>
                <titulo>Capítulo 1</titulo>
                <pagina>1</pagina>
            </indice>
            <indice>
                <titulo>Capítulo 2</titulo>
                <pagina>5</pagina>
            </indice>
        </indices>';

        $file = UploadedFile::fake()->createWithContent('indices.xml', $xmlContent);

        $response = $this->actingAs($user)->postJson("/api/v1/livros/{$livro->id}/importar-indices-xml", [
            'xml_file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Índices importados com sucesso! =)']);

        $this->assertDatabaseHas('indices', ['titulo' => 'Capítulo 1', 'pagina' => 1]);
        $this->assertDatabaseHas('indices', ['titulo' => 'Capítulo 2', 'pagina' => 5]);
    }
}
