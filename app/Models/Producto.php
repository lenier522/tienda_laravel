<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'cantidad',
        'imagen'
    ];




    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }


     // Relación con compras a través de la tabla intermedia
     public function compras()
     {
         return $this->belongsToMany(Compra::class, 'compra_producto')
                     ->withPivot('cantidad', 'precio')
                     ->withTimestamps();
     }
}
