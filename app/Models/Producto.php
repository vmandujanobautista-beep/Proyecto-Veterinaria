<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'categoria',
        'precio',
        'stock',
        'descripcion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function ventaProductos(): HasMany
    {
        return $this->hasMany(VentaProducto::class);
    }
}
