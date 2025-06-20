<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrador';
    }

    /**
     * Verificar si el usuario es de ventanilla
     */
    public function isVentanilla(): bool
    {
        return $this->role === 'ventanilla';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Verificar si el usuario puede acceder a funciones administrativas
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Verificar si el usuario puede ver reportes
     */
    public function canViewReports(): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Verificar si el usuario puede gestionar otros usuarios
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Verificar si el usuario puede configurar el sistema
     */
    public function canConfigureSystem(): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Verificar si el usuario puede ver logs del sistema
     */
    public function canViewLogs(): bool
    {
        return $this->isAdmin() && $this->isActive();
    }

    /**
     * Obtener el nombre del rol en formato legible
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'administrador' => 'Administrador',
            'ventanilla' => 'Ventanilla',
            default => 'Usuario'
        };
    }

    /**
     * Relación con radicados que el usuario ha creado
     */
    public function radicados(): HasMany
    {
        return $this->hasMany(Radicado::class, 'usuario_radica_id');
    }

    /**
     * Relación con radicados que el usuario ha respondido
     */
    public function radicadosRespondidos(): HasMany
    {
        return $this->hasMany(Radicado::class, 'usuario_responde_id');
    }
}
