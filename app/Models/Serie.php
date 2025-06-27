<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable = [
        'unidad_administrativa_id',
        'numero_serie',
        'nombre',
        'descripcion',
        'dias_respuesta',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'dias_respuesta' => 'integer',
    ];

    /**
     * Relación con unidad administrativa
     */
    public function unidadAdministrativa(): BelongsTo
    {
        return $this->belongsTo(UnidadAdministrativa::class);
    }

    /**
     * Relación con subseries
     */
    public function subseries(): HasMany
    {
        return $this->hasMany(Subserie::class);
    }

    /**
     * Scope para series activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Obtener la descripción completa de la serie
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return "Código: {$this->unidadAdministrativa->codigo}. Número de Serie: {$this->numero_serie}. Nombre Serie: {$this->nombre}";
    }

    /**
     * Obtener el código completo de la serie
     */
    public function getCodigoCompletoAttribute(): string
    {
        return "{$this->unidadAdministrativa->codigo}-{$this->numero_serie}";
    }
}
