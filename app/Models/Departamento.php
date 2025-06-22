<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Relación con ciudades
     */
    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class);
    }

    /**
     * Scope para departamentos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por nombre
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('nombre');
    }
}
