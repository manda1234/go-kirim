<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    protected $fillable = ['order_id', 'sender_id', 'sender_role', 'message', 'is_read', 'image_path', 'type'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────────────
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
