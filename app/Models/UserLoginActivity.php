<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginActivity extends Model
{
    protected $table = 'user_login_activities';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'remember_me',
        'session_id',
        'login_at',
    ];

    protected $casts = [
        'remember_me' => 'boolean',
        'login_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener logins recientes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('login_at', '>=', now()->subDays($days));
    }

    /**
     * Scope para obtener logins por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener el navegador desde el user agent
     */
    public function getBrowserAttribute(): string
    {
        $userAgent = $this->user_agent;
        
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } else {
            return 'Otro';
        }
    }

    /**
     * Obtener el sistema operativo desde el user agent
     */
    public function getOperatingSystemAttribute(): string
    {
        $userAgent = $this->user_agent;
        
        if (strpos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            return 'iOS';
        } else {
            return 'Otro';
        }
    }
}
