<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait HasCreator
{
    /**
     * The "booted" method of the trait.
     */
    protected static function bootHasCreator(): void
    {
        // Asignar automáticamente el user_id al crear el registro
        // No aplicamos un Global Scope aquí para que los registros sean compartidos entre usuarios.
        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    /**
     * Get the user that created the model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
