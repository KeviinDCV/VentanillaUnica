<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadAdministrativa extends Model
{
    protected $table = 'unidades_administrativas';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Relación con series
     */
    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }

    /**
     * Scope para unidades administrativas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Obtener la descripción completa de la unidad administrativa
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return "{$this->codigo} - {$this->nombre}";
    }

    /**
     * Obtener todas las subseries de esta unidad administrativa
     */
    public function subseries()
    {
        return $this->hasManyThrough(Subserie::class, Serie::class);
    }
}
