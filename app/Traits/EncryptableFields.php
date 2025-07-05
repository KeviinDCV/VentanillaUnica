<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

trait EncryptableFields
{
    /**
     * Campos que deben ser encriptados
     * Sobrescribir en el modelo que use este trait
     */
    protected $encryptable = [];

    /**
     * Boot del trait
     */
    public static function bootEncryptableFields()
    {
        // Encriptar antes de guardar
        static::saving(function ($model) {
            $model->encryptFields();
        });

        // Desencriptar después de recuperar
        static::retrieved(function ($model) {
            $model->decryptFields();
        });
    }

    /**
     * Encriptar campos sensibles
     */
    protected function encryptFields()
    {
        foreach ($this->getEncryptableFields() as $field) {
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                try {
                    // Solo encriptar si no está ya encriptado
                    if (!$this->isEncrypted($this->attributes[$field])) {
                        $this->attributes[$field] = Crypt::encrypt($this->attributes[$field]);
                    }
                } catch (\Exception $e) {
                    Log::warning("Error encriptando campo {$field}", [
                        'model' => get_class($this),
                        'id' => $this->id ?? 'nuevo',
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Desencriptar campos sensibles
     */
    protected function decryptFields()
    {
        foreach ($this->getEncryptableFields() as $field) {
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                try {
                    // Solo desencriptar si está encriptado
                    if ($this->isEncrypted($this->attributes[$field])) {
                        $this->attributes[$field] = Crypt::decrypt($this->attributes[$field]);
                    }
                } catch (\Exception $e) {
                    Log::warning("Error desencriptando campo {$field}", [
                        'model' => get_class($this),
                        'id' => $this->id ?? 'desconocido',
                        'error' => $e->getMessage()
                    ]);
                    
                    // En caso de error, mantener el valor original
                    // Esto evita que se rompa la aplicación
                }
            }
        }
    }

    /**
     * Verificar si un valor está encriptado
     */
    protected function isEncrypted($value)
    {
        try {
            Crypt::decrypt($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener campos encriptables
     */
    protected function getEncryptableFields()
    {
        return property_exists($this, 'encryptable') ? $this->encryptable : [];
    }

    /**
     * Accessor para campos encriptados (para búsquedas)
     */
    public function getDecryptedAttribute($field)
    {
        if (in_array($field, $this->getEncryptableFields())) {
            $value = $this->attributes[$field] ?? null;
            
            if ($value && $this->isEncrypted($value)) {
                try {
                    return Crypt::decrypt($value);
                } catch (\Exception $e) {
                    Log::warning("Error accediendo campo encriptado {$field}", [
                        'model' => get_class($this),
                        'id' => $this->id
                    ]);
                    return null;
                }
            }
            
            return $value;
        }
        
        return $this->attributes[$field] ?? null;
    }

    /**
     * Scope para buscar en campos encriptados
     * NOTA: Esto es lento, usar solo cuando sea necesario
     */
    public function scopeWhereEncrypted($query, $field, $value)
    {
        if (!in_array($field, $this->getEncryptableFields())) {
            return $query->where($field, $value);
        }

        // Para campos encriptados, necesitamos buscar de forma diferente
        // Esto es menos eficiente pero más seguro
        return $query->get()->filter(function ($item) use ($field, $value) {
            try {
                $decryptedValue = $item->getDecryptedAttribute($field);
                return $decryptedValue === $value;
            } catch (\Exception $e) {
                return false;
            }
        });
    }

    /**
     * Método para migrar datos existentes a encriptados
     */
    public static function migrateToEncrypted($batchSize = 100)
    {
        $model = new static();
        $encryptableFields = $model->getEncryptableFields();
        
        if (empty($encryptableFields)) {
            return;
        }

        Log::info("Iniciando migración de encriptación para " . get_class($model));

        static::chunk($batchSize, function ($records) use ($encryptableFields) {
            foreach ($records as $record) {
                $needsUpdate = false;
                
                foreach ($encryptableFields as $field) {
                    if (isset($record->attributes[$field]) && 
                        !empty($record->attributes[$field]) && 
                        !$record->isEncrypted($record->attributes[$field])) {
                        
                        try {
                            $record->attributes[$field] = Crypt::encrypt($record->attributes[$field]);
                            $needsUpdate = true;
                        } catch (\Exception $e) {
                            Log::error("Error migrando registro {$record->id} campo {$field}: " . $e->getMessage());
                        }
                    }
                }
                
                if ($needsUpdate) {
                    $record->saveQuietly(); // Sin disparar eventos
                }
            }
        });

        Log::info("Migración de encriptación completada para " . get_class($model));
    }

    /**
     * Método para obtener versión enmascarada de un campo
     */
    public function getMaskedAttribute($field, $maskChar = '*', $visibleStart = 2, $visibleEnd = 2)
    {
        $value = $this->getDecryptedAttribute($field);
        
        if (!$value || strlen($value) <= ($visibleStart + $visibleEnd)) {
            return $value;
        }
        
        $start = substr($value, 0, $visibleStart);
        $end = substr($value, -$visibleEnd);
        $middle = str_repeat($maskChar, strlen($value) - $visibleStart - $visibleEnd);
        
        return $start . $middle . $end;
    }
}
