<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Role Helpers ───

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Cek apakah user memiliki salah satu role yang diberikan.
     * Contoh: $user->hasRole('manager', 'admin')
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'manager' => 'Manager',
            'admin'   => 'Admin / Staff Gudang',
            'kasir'   => 'Kasir',
            default   => ucfirst($this->role),
        };
    }

    // ─── Relationships ───

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function stockNotifikasis(): HasMany
    {
        return $this->hasMany(StockNotifikasi::class);
    }
}
