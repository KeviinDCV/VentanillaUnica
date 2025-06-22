<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = [
        'nombre',
        'codigo',
        'departamento_id',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /**
     * Relación con departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Scope para ciudades activas
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

    /**
     * Scope para filtrar por departamento
     */
    public function scopePorDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    /**
     * Accessor para nombre completo (Ciudad, Departamento)
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ', ' . $this->departamento->nombre;
    }
}
