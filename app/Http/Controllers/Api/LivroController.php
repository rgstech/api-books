<?php

namespace App\Http\Controllers\Api;

use App\Models\Livro;
//use App\Models\Indice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//controlador do livro
class LivroController extends Controller
{
    public function index(Request $request)
    {
        $query = Livro::query();
         //filtros
        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->has('titulo_do_indice')) {
            $query->whereHas('indices', function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->titulo_do_indice . '%');
            });
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'indices' => 'nullable|array',
            'indices.*.titulo' => 'required|string|max:255',
            'indices.*.pagina' => 'required|integer',
        ]);

        $livro = Livro::create([
            'usuario_publicador_id' => $request->user()->id,
            'titulo' => $data['titulo'],
        ]);

        if (isset($data['indices'])) {
            foreach ($data['indices'] as $indiceData) {
                $livro->indices()->create($indiceData);
            }
        }

        return response()->json($livro, 201);
    }

    public function importarIndicesXml(Request $request, $livroId)
    {
        $livro = Livro::findOrFail($livroId);

        // considerando que o XML seja enviado como arquivo
        $xml = simplexml_load_file($request->file('xml'));

        foreach ($xml->indice as $indiceData) {
            $livro->indices()->create([
                'titulo' => (string)$indiceData->titulo,
                'pagina' => (int)$indiceData->pagina,
            ]);
        }

        return response()->json(['message' => 'Índices importados com sucesso! =)']);
    }
}
