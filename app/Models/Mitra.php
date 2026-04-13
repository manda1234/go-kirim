<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Mitra extends Model
{
    protected $fillable = [
        'name', 'email', 'status', 'is_online',
        'vehicle', 'rating', 'total_trip',
    ];

    protected $casts = [
        'is_online' => 'boolean',
    ];

    // Scope: hanya mitra aktif (bisa terima order)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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