<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusRate extends Model
{
    protected $fillable = [
        'tier_name',
        'min_orders',
        'max_orders',
        'bonus_amount',
        'rating_bonus',
        'rating_threshold',
        'is_active',
    ];
}