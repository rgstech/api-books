<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indice extends Model
{
    use HasFactory;

    protected $fillable = ['livro_id', 'indice_pai_id', 'titulo', 'pagina'];

    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    public function indicePai()
    {
        return $this->belongsTo(Indice::class, 'indice_pai_id');
    }

    public function indicesFilhos()
    {
        return $this->hasMany(Indice::class, 'indice_pai_id');
    }
}
