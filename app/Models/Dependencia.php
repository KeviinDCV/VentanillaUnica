<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dependencia extends Model
{
    protected $table = 'dependencias';

    protected $fillable = [
        'codigo',
        'nombre',
        'sigla',
        'descripcion',
        'responsable',
        'telefono',
        'email',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    /**
     * Relación con radicados donde esta dependencia es el destino
     */
    public function radicadosDestino(): HasMany
    {
        return $this->hasMany(Radicado::class, 'dependencia_destino_id');
    }

    /**
     * Relación con radicados donde esta dependencia es el origen
     */
    public function radicadosOrigen(): HasMany
    {
        return $this->hasMany(Radicado::class, 'dependencia_origen_id');
    }

    /**
     * Scope para dependencias activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Obtener el nombre completo con sigla
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->sigla ? "{$this->nombre} ({$this->sigla})" : $this->nombre;
    }
}
