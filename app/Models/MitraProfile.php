<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraProfile extends Model
{
    protected $fillable = [
        'user_id','vehicle_type','vehicle_brand','vehicle_plate',
        'ktp_number','sim_number','ktp_photo','sim_photo',
        'latitude','longitude','is_online','rating',
        'total_trips','total_earnings','status',
    ];

    protected $casts = [
        'is_online'      => 'boolean',
        'rating'         => 'float',
        'total_earnings' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getVehicleLabelAttribute(): string
    {
        return ucfirst($this->vehicle_type) . ' · ' . $this->vehicle_brand . ' · ' . $this->vehicle_plate;
    }
}