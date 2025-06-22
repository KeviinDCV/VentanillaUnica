<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'tipos_solicitud';

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
     * Scope para tipos de solicitud activos
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
     * Obtener el nombre completo con código
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->codigo ? "{$this->nombre} ({$this->codigo})" : $this->nombre;
    }
}
