<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'avatar', 'balance'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isCinemaAdmin() || $this->isCafeAdmin();
    }

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
            'balance' => 'decimal:2',
        ];
    }

    // === Role Helpers ===

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isCinemaAdmin(): bool
    {
        return $this->role === 'cinema_admin';
    }

    public function isCafeAdmin(): bool
    {
        return $this->role === 'cafe_admin';
    }

    public function isAdmin(): bool
    {
        return $this->isCinemaAdmin();
    }

    // === Relationships ===

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
