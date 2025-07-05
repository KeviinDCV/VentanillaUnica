<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comunicacion extends Model
{
    use HasFactory;

    protected $table = 'comunicaciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con radicados
     */
    public function radicados(): HasMany
    {
        return $this->hasMany(Radicado::class, 'tipo_comunicacion', 'codigo');
    }

    /**
     * Scope para comunicaciones activas
     */
    public function scopeActivos($query)
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
