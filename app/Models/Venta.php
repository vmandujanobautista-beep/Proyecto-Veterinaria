<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToUser;

class Venta extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'total',
        'estado',
        'metodo_pago',
        'cliente_id',
        'mascota_id',
        'user_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class)->withoutGlobalScope('user_id');
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class)->withoutGlobalScope('user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ventaProductos(): HasMany
    {
        return $this->hasMany(VentaProducto::class);
    }
}
