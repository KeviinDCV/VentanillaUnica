<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Radicado extends Model
{
    protected $table = 'radicados';

    protected $fillable = [
        'numero_radicado',
        'tipo',
        'fecha_radicado',
        'hora_radicado',
        'remitente_id',
        'trd_id',
        'dependencia_destino_id',
        'dependencia_origen_id',
        'usuario_radica_id',
        'medio_recepcion',
        'tipo_comunicacion',
        'numero_folios',
        'observaciones',
        'medio_respuesta',
        'tipo_anexo',
        'fecha_limite_respuesta',
        'estado',
        'respuesta',
        'fecha_respuesta',
        'usuario_responde_id',
    ];

    protected $casts = [
        'fecha_radicado' => 'date',
        'hora_radicado' => 'datetime:H:i:s',
        'fecha_limite_respuesta' => 'date',
        'fecha_respuesta' => 'date',
        'numero_folios' => 'integer',
    ];

    /**
     * Relación con el remitente
     */
    public function remitente(): BelongsTo
    {
        return $this->belongsTo(Remitente::class, 'remitente_id');
    }

    /**
     * Relación con TRD
     */
    public function trd(): BelongsTo
    {
        return $this->belongsTo(Trd::class, 'trd_id');
    }

    /**
     * Relación con dependencia destino
     */
    public function dependenciaDestino(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_destino_id');
    }

    /**
     * Relación con dependencia origen
     */
    public function dependenciaOrigen(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_origen_id');
    }

    /**
     * Relación con usuario que radica
     */
    public function usuarioRadica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_radica_id');
    }

    /**
     * Relación con usuario que responde
     */
    public function usuarioResponde(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_responde_id');
    }

    /**
     * Relación con documentos
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'radicado_id');
    }

    /**
     * Obtener el documento principal
     */
    public function documentoPrincipal()
    {
        return $this->documentos()->where('es_principal', true)->first();
    }

    /**
     * Scopes para filtrar por estado
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnProceso($query)
    {
        return $query->where('estado', 'en_proceso');
    }

    public function scopeRespondidos($query)
    {
        return $query->where('estado', 'respondido');
    }

    /**
     * Scope para radicados por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Verificar si el radicado está vencido
     */
    public function estaVencido(): bool
    {
        if (!$this->fecha_limite_respuesta) {
            return false;
        }

        return Carbon::now()->isAfter($this->fecha_limite_respuesta) &&
               !in_array($this->estado, ['respondido', 'archivado']);
    }

    /**
     * Obtener días restantes para respuesta
     */
    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->fecha_limite_respuesta) {
            return null;
        }

        return Carbon::now()->diffInDays($this->fecha_limite_respuesta, false);
    }

    /**
     * Generar número de radicado único
     */
    public static function generarNumeroRadicado($tipo = 'entrada'): string
    {
        $año = date('Y');
        $prefijo = match($tipo) {
            'entrada' => 'E',
            'interno' => 'I',
            'salida' => 'S',
            default => 'E'
        };

        // Obtener el último número del año actual
        $ultimoRadicado = self::where('numero_radicado', 'like', "{$prefijo}-{$año}-%")
                             ->orderBy('numero_radicado', 'desc')
                             ->first();

        if ($ultimoRadicado) {
            $partes = explode('-', $ultimoRadicado->numero_radicado);
            $ultimoNumero = intval($partes[2] ?? 0);
        } else {
            $ultimoNumero = 0;
        }

        $nuevoNumero = str_pad($ultimoNumero + 1, 6, '0', STR_PAD_LEFT);

        return "{$prefijo}-{$año}-{$nuevoNumero}";
    }
}
