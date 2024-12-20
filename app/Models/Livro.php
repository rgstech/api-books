<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Livro extends Model //modelo para livros
{
    use HasFactory;

    protected $fillable = ['usuario_publicador_id', 'titulo'];

    public function indices()
    {
        return $this->hasMany(Indice::class);
    }
}
