<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LivroController;


Route::prefix('v1')->group(function () {
    Route::post('auth/token', [AuthController::class, 'token']);

    Route::middleware('auth:sanctum')->group(function () { // rotas protegidas pelo middleware com sanctum
        Route::get('livros', [LivroController::class, 'index']); // listar livros
        Route::post('livros', [LivroController::class, 'store']); //armazenar livro
        Route::post('livros/{livroId}/importar-indices-xml', [LivroController::class, 'importarIndicesXml']);
        //importação por arquivo xml
    });
});
