<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'role', 'avatar', 'is_active','photo','status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // ---- Relationships ----

    public function mitraProfile()
    {
        return $this->hasOne(MitraProfile::class);
    }

    public function ordersAsCustomer()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function ordersAsMitra()
    {
        return $this->hasMany(Order::class, 'mitra_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function savedAddresses()
    {
        return $this->hasMany(SavedAddress::class);
    }

    public function earnings()
    {
        return $this->hasMany(MitraEarning::class, 'mitra_id');
    }

    // ---- Helpers ----

    public function isCustomer(): bool { return $this->role === 'customer'; }
    public function isMitra(): bool    { return $this->role === 'mitra'; }
    public function isAdmin(): bool    { return $this->role === 'admin'; }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

public function getAvatarUrlAttribute(): string
{
    $foto = $this->avatar ?? $this->photo ?? null;

    return $foto
        ? asset('storage/' . $foto)
        : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=16a34a&color=fff';
}

public function isSuspended(): bool
{
    return $this->status === 'suspended';
}

public function isActive(): bool
{
    return $this->status === 'active';
}
    
}