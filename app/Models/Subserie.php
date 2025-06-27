<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subserie extends Model
{
    protected $table = 'subseries';

    protected $fillable = [
        'serie_id',
        'numero_subserie',
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
     * Relación con serie
     */
    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    /**
     * Scope para subseries activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Obtener la descripción completa de la subserie
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return "Código: {$this->serie->unidadAdministrativa->codigo}. Serie: {$this->serie->numero_serie} {$this->serie->nombre}. Sub-serie: {$this->numero_subserie} Nombre Sub-serie: {$this->nombre}";
    }

    /**
     * Obtener el código completo de la subserie
     */
    public function getCodigoCompletoAttribute(): string
    {
        return "{$this->serie->unidadAdministrativa->codigo}-{$this->serie->numero_serie}-{$this->numero_subserie}";
    }

    /**
     * Obtener los días de respuesta (prioriza subserie, luego serie)
     */
    public function getDiasRespuestaEfectivoAttribute(): ?int
    {
        return $this->dias_respuesta ?? $this->serie->dias_respuesta;
    }
}
