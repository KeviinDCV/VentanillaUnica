<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trd extends Model
{
    protected $table = 'trd';

    protected $fillable = [
        'codigo',
        'serie',
        'subserie',
        'asunto',
        'retencion_archivo_gestion',
        'retencion_archivo_central',
        'disposicion_final',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'retencion_archivo_gestion' => 'integer',
        'retencion_archivo_central' => 'integer',
    ];

    /**
     * Relación con radicados
     */
    public function radicados(): HasMany
    {
        return $this->hasMany(Radicado::class, 'trd_id');
    }

    /**
     * Scope para TRD activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener la descripción completa del TRD
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $descripcion = "{$this->codigo} - {$this->serie}";
        if ($this->subserie) {
            $descripcion .= " / {$this->subserie}";
        }
        return $descripcion;
    }

    /**
     * Obtener el tiempo total de retención
     */
    public function getTiempoTotalRetencionAttribute(): int
    {
        return $this->retencion_archivo_gestion + $this->retencion_archivo_central;
    }
}
