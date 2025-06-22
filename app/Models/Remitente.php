<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Remitente extends Model
{
    protected $table = 'remitentes';

    protected $fillable = [
        'tipo',
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'departamento',
        'entidad',
        'observaciones',
    ];

    /**
     * Relación con radicados
     */
    public function radicados(): HasMany
    {
        return $this->hasMany(Radicado::class, 'remitente_id');
    }

    /**
     * Scope para remitentes anónimos
     */
    public function scopeAnonimos($query)
    {
        return $query->where('tipo', 'anonimo');
    }

    /**
     * Scope para remitentes registrados
     */
    public function scopeRegistrados($query)
    {
        return $query->where('tipo', 'registrado');
    }

    /**
     * Verificar si el remitente es anónimo
     */
    public function esAnonimo(): bool
    {
        return $this->tipo === 'anonimo';
    }

    /**
     * Obtener la identificación completa
     */
    public function getIdentificacionCompletaAttribute(): string
    {
        if ($this->esAnonimo()) {
            return 'Anónimo';
        }

        return $this->tipo_documento && $this->numero_documento
            ? "{$this->tipo_documento} {$this->numero_documento}"
            : 'Sin documento';
    }

    /**
     * Obtener información de contacto
     */
    public function getContactoCompletoAttribute(): string
    {
        $contacto = [];

        if ($this->telefono) {
            $contacto[] = "Tel: {$this->telefono}";
        }

        if ($this->email) {
            $contacto[] = "Email: {$this->email}";
        }

        return implode(' | ', $contacto) ?: 'Sin contacto';
    }
}
