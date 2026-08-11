<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait BelongsToUser
{
    /**
     * The "booted" method of the trait.
     */
    protected static function bootBelongsToUser(): void
    {
        // Filtro global: solo selecciona registros del usuario autenticado
        // El scope se ignora automáticamente en relaciones belongsTo de otros modelos
        // ya que Laravel no propaga global scopes del modelo padre al modelo hijo.
        static::addGlobalScope('user_id', function (Builder $builder) {
            if (Auth::check()) {
                // Solo aplicar si la query es una query "raíz" (no una subquery de relación)
                $builder->where($builder->getModel()->getTable() . '.user_id', Auth::id());
            }
        });

        // Asignar automáticamente el user_id al crear el registro
        static::creating(function ($model) {
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    /**
     * Override belongsTo to always load the related model without the user_id scope.
     * This fixes the issue where $mascota->cliente returns null because the Cliente
     * global scope filters by the current user's ID.
     */
    public function belongsToWithoutScope(string $related, string $foreignKey = null, string $ownerKey = null, string $relation = null): BelongsTo
    {
        $relation = $this->belongsTo($related, $foreignKey, $ownerKey, $relation);
        return $relation->withoutGlobalScope('user_id');
    }

    /**
     * Get the user that owns the model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
