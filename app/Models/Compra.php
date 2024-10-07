<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id', 'total'];

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con productos a través de la tabla intermedia
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'compra_producto')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }
}
