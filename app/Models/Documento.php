<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'radicado_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'tamaño_archivo',
        'hash_archivo',
        'descripcion',
        'es_principal',
        'es_digitalizado',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'es_digitalizado' => 'boolean',
        'tamaño_archivo' => 'integer',
    ];

    /**
     * Relación con radicado
     */
    public function radicado(): BelongsTo
    {
        return $this->belongsTo(Radicado::class, 'radicado_id');
    }

    /**
     * Scope para documentos principales
     */
    public function scopePrincipales($query)
    {
        return $query->where('es_principal', true);
    }

    /**
     * Scope para documentos digitalizados
     */
    public function scopeDigitalizados($query)
    {
        return $query->where('es_digitalizado', true);
    }

    /**
     * Obtener el tamaño del archivo en formato legible
     */
    public function getTamañoLegibleAttribute(): string
    {
        $bytes = $this->tamaño_archivo;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Verificar si el archivo existe
     */
    public function archivoExiste(): bool
    {
        return Storage::disk('public')->exists($this->ruta_archivo);
    }

    /**
     * Obtener la URL del archivo
     */
    public function getUrlArchivoAttribute(): string
    {
        return Storage::disk('public')->url($this->ruta_archivo);
    }

    /**
     * Verificar la integridad del archivo
     */
    public function verificarIntegridad(): bool
    {
        if (!$this->archivoExiste()) {
            return false;
        }

        $contenido = Storage::get($this->ruta_archivo);
        $hashActual = hash('sha256', $contenido);

        return $hashActual === $this->hash_archivo;
    }

    /**
     * Obtener el tipo de archivo basado en la extensión
     */
    public function getTipoArchivoAttribute(): string
    {
        $extension = pathinfo($this->nombre_archivo, PATHINFO_EXTENSION);

        return match(strtolower($extension)) {
            'pdf' => 'PDF',
            'doc', 'docx' => 'Word',
            'xls', 'xlsx' => 'Excel',
            'jpg', 'jpeg', 'png', 'gif' => 'Imagen',
            'txt' => 'Texto',
            default => 'Otro'
        };
    }
}
