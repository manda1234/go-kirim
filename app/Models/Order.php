<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

   protected $fillable = [
    'order_code', 'customer_id', 'mitra_id',
    'service_type', 'vehicle_type',
    'pickup_address', 'pickup_lat', 'pickup_lng',
    'destination_address', 'destination_lat', 'destination_lng',
    'item_type', 'item_weight', 'notes',
    'distance_km', 'base_fare', 'distance_fare', 'service_fee',
    'total_fare', 'platform_fee', 'mitra_earning',
    'status', 'payment_method', 'payment_status',
    'payment_proof', 'paid_at', 'paid_by',
    'bank_selected', 'ewallet_selected', // ← tambahkan ini
    'customer_rating', 'customer_review',
    'accepted_at', 'picked_up_at', 'delivered_at',
    'completed_at', 'cancelled_at', 'cancel_reason', 'cancelled_by',
    'on_the_way_at', 'arrived_at'
];

    protected $casts = [
        'accepted_at'   => 'datetime',
        'picked_up_at'  => 'datetime',
        'delivered_at'  => 'datetime',
        'completed_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
        'on_the_way_at' => 'datetime',
        'arrived_at'    => 'datetime',
        'paid_at'       => 'datetime',
    ];

    // ────────────────────────────────────────────────────────────
    // Relationships
    // ────────────────────────────────────────────────────────────

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class)->orderBy('created_at');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ────────────────────────────────────────────────────────────
    // Accessors (Getters)
    // ────────────────────────────────────────────────────────────

    public function getPaymentProofUrlAttribute(): ?string
    {
        return $this->payment_proof
            ? asset('storage/' . $this->payment_proof)
            : null;
    }

    public function getPaymentStatusBadgeAttribute(): array
    {
        return match ($this->payment_status) {
            'unpaid'   => ['label' => 'Belum Bayar',           'class' => 'bdg-red'],
            'pending'  => ['label' => 'Menunggu Konfirmasi',   'class' => 'bdg-yellow'],
            'paid'     => ['label' => 'Sudah Dibayar',         'class' => 'bdg-green'],
            'refunded' => ['label' => 'Dikembalikan',          'class' => 'bdg-gray'],
            default    => ['label' => 'Tidak Diketahui',       'class' => 'bdg-gray'],
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'waiting'     => ['label' => 'Menunggu',        'class' => 'bdg-yellow'],
            'accepted'    => ['label' => 'Diterima',         'class' => 'bdg-blue'],
            'picking_up'  => ['label' => 'Menuju Pickup',    'class' => 'bdg-blue'],
            'on_the_way'  => ['label' => 'Menuju Lokasi',    'class' => 'bdg-blue'],
            'in_progress' => ['label' => 'Dalam Perjalanan', 'class' => 'bdg-blue'],
            'picked_up'   => ['label' => 'Dijemput',         'class' => 'bdg-blue'],
            'delivered'   => ['label' => 'Diantar',          'class' => 'bdg-blue'],
            'arrived'     => ['label' => 'Sampai Tujuan',    'class' => 'bdg-green'],
            'completed'   => ['label' => 'Selesai',          'class' => 'bdg-green'],
            'cancelled'   => ['label' => 'Dibatalkan',       'class' => 'bdg-red'],
            default       => ['label' => ucfirst($this->status), 'class' => 'bdg-gray'],
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'     => 'Tunai',
            'transfer' => 'Transfer Bank',
            'qris'     => 'QRIS',
            'e_wallet' => 'E-Wallet',
            default    => ucfirst($this->payment_method),
        };
    }

    public function getServiceLabelAttribute(): string
    {
        return match ($this->service_type) {
            'kirim_barang'  => 'Kirim Barang',
            'antar_orang'   => 'Antar Orang',
            'food_delivery' => 'Food Delivery',
            default         => ucfirst(str_replace('_', ' ', $this->service_type)),
        };
    }

    // ────────────────────────────────────────────────────────────
    // Logic: Status & Button Helpers
    // ────────────────────────────────────────────────────────────

    public function isRide(): bool
    {
        return $this->service_type === 'antar_orang';
    }

    public function getNextStatus(): ?array
    {
        if ($this->isRide()) {
            return match ($this->status) {
                'waiting'    => ['status' => 'accepted',   'timestamp' => 'accepted_at'],
                'accepted'   => ['status' => 'on_the_way',  'timestamp' => 'on_the_way_at'],
                'on_the_way' => ['status' => 'picked_up',   'timestamp' => 'picked_up_at'],
                'picked_up'  => ['status' => 'arrived',     'timestamp' => 'arrived_at'],
                'arrived'    => ['status' => 'completed',   'timestamp' => 'completed_at'],
                default      => null,
            };
        }

        // kirim_barang & food_delivery
        return match ($this->status) {
            'waiting'     => ['status' => 'accepted',    'timestamp' => 'accepted_at'],
            'accepted'    => ['status' => 'picking_up',  'timestamp' => null],
            'picking_up'  => ['status' => 'in_progress', 'timestamp' => 'picked_up_at'],
            'in_progress' => ['status' => 'delivered',   'timestamp' => 'delivered_at'],
            'delivered'   => ['status' => 'completed',   'timestamp' => 'completed_at'],
            default       => null,
        };
    }

    public function getNextButtonLabel(): string
    {
        if ($this->isRide()) {
            return match ($this->status) {
                'waiting'    => 'Terima Pesanan',
                'accepted'   => 'Saya Menuju Lokasi',
                'on_the_way' => 'Penumpang Dijemput',
                'picked_up'  => 'Mulai Perjalanan',
                'arrived'    => 'Selesaikan Perjalanan',
                default      => 'Proses',
            };
        }

        return match ($this->status) {
            'waiting'     => 'Terima Pesanan',
            'accepted'    => 'Menuju Lokasi Pickup',
            'picking_up'  => 'Barang Sudah Diambil',
            'in_progress' => 'Tandai Sudah Dikirim',
            'delivered'   => 'Selesaikan Pesanan',
            default       => 'Proses',
        };
    }

    public function getTrackingDescription(string $status): string
    {
        if ($this->isRide()) {
            return match ($status) {
                'accepted'   => 'Driver menerima pesanan Anda',
                'on_the_way' => 'Driver sedang menuju lokasi penjemputan',
                'picked_up'  => 'Penumpang berhasil dijemput, perjalanan dimulai',
                'arrived'    => 'Penumpang telah sampai di tujuan',
                'completed'  => 'Perjalanan selesai',
                'cancelled'  => 'Pesanan dibatalkan',
                default      => ucfirst(str_replace('_', ' ', $status)),
            };
        }

        return match ($status) {
            'accepted'    => 'Mitra menerima pesanan Anda',
            'picking_up'  => 'Mitra sedang menuju lokasi pickup',
            'in_progress' => 'Barang sedang dalam perjalanan ke tujuan',
            'delivered'   => 'Barang telah sampai di tujuan',
            'completed'   => 'Pesanan selesai dikonfirmasi',
            'cancelled'   => 'Pesanan dibatalkan',
            default       => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    // ────────────────────────────────────────────────────────────
    // Helper Methods — Payment
    // ────────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        if ($this->payment_method === 'cash') {
            return $this->status === 'completed';
        }

        return $this->payment_status === 'paid';
    }

    public function needsProofUpload(): bool
    {
        return in_array($this->payment_method, ['transfer', 'qris'])
            && !$this->isPaid();
    }

    public function isWaitingConfirmation(): bool
    {
        return $this->payment_status === 'pending'
            && $this->payment_proof !== null;
    }

    public function requiresManualConfirmation(): bool
    {
        return in_array($this->payment_method, ['transfer', 'qris']);
    }

    // ────────────────────────────────────────────────────────────
    // Helper Methods — Order State
    // ────────────────────────────────────────────────────────────

    public function isCancellable(): bool
    {
        return in_array($this->status, ['waiting', 'accepted']);
    }

    public function isActive(): bool
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    // ────────────────────────────────────────────────────────────
    // Scopes
    // ────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeUnpaidProof($query)
    {
        return $query->whereIn('payment_method', ['transfer', 'qris'])
                     ->where('payment_status', 'pending')
                     ->whereNotNull('payment_proof');
    }

    // ────────────────────────────────────────────────────────────
    // Static Helpers
    // ────────────────────────────────────────────────────────────

    public static function generateCode(): string
    {
        $prefix = 'ORD';
        $date   = now()->format('ymd');
        $last   = static::whereDate('created_at', today())->count() + 1;

        return $prefix . '-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public static function initialPaymentStatus(string $paymentMethod): string
    {
        return match ($paymentMethod) {
            'cash'             => 'pending',
            'transfer', 'qris' => 'unpaid',
            default            => 'unpaid',
        };
    }
}