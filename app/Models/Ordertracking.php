<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    public $timestamps  = false;
    const CREATED_AT    = 'created_at';

    protected $fillable = [
        'order_id',
        'status',
        'description', // ✅ deskripsi otomatis per status
        'latitude',
        'longitude',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function order()     { return $this->belongsTo(Order::class); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }

    // ✅ Deskripsi otomatis berdasarkan status
    public static function descriptionFor(string $status): string
    {
        return match($status) {
            // Delivery
            'waiting'     => 'Pesanan dibuat, mencari mitra terdekat',
            'accepted'    => 'Mitra menerima pesanan',
            'picking_up'  => 'Mitra menuju lokasi pickup barang',
            'in_progress' => 'Barang sedang dalam perjalanan ke tujuan',
            'delivered'   => 'Barang telah sampai di tujuan',
            'completed'   => 'Pesanan selesai dikonfirmasi',
            'cancelled'   => 'Pesanan dibatalkan',
            // Ride
            'on_the_way'  => 'Driver sedang menuju lokasi penjemputan',
            'picked_up'   => 'Penumpang telah dijemput, perjalanan dimulai',
            'arrived'     => 'Penumpang telah sampai di tujuan',
            default       => ucfirst($status),
        };
    }
}