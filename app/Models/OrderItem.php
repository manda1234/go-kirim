<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ─── OrderItem ────────────────────────────────────────────────
class OrderItem extends Model
{
    protected $fillable = ['order_id','item_name','restaurant_name','quantity','unit_price','subtotal','notes'];
    public function order() { return $this->belongsTo(Order::class); }
}