<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Order, MitraProfile, ServiceRate, MitraEarning};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Storage};
use App\Services\BonusService;
use App\Models\BonusRate;

class AdminController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        $todayOrders = Order::today()->count();
        $todayRevenue = Order::today()->completed()->sum('total_fare');
        $totalCustomers = User::where('role', 'customer')->count();
        $activeMitras = MitraProfile::where('is_online', true)
            ->whereHas('user', function ($q) {
                $q->where('is_active', true)->where('status', '!=', 'suspended');
            })
            ->count();
        $totalMitras = User::where('role', 'mitra')->count();
        $pendingOrders = Order::where('status', 'waiting')->count();

        $recentOrders = Order::with('customer', 'mitra')->latest()->take(10)->get();

        $orderStats = Order::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        // Revenue last 7 days
        $revenueChart = Order::completed()
            ->selectRaw('DATE(completed_at) as date, SUM(total_fare) as total')
            ->where('completed_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('admin.dashboard', compact('todayOrders', 'todayRevenue', 'totalCustomers', 'activeMitras', 'totalMitras', 'pendingOrders', 'recentOrders', 'orderStats', 'revenueChart'));
    }

    // ── Manajemen Transaksi ────────────────────────────────────
    public function transactions(Request $request)
    {
        
        $query = Order::with('customer', 'mitra')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('order_code', 'like', "%$s%")->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$s%")));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();
        return view('admin.transactions', compact('orders'));
    }

    // ── Manajemen Customer ─────────────────────────────────────
    public function customers(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('ordersAsCustomer')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%"),
            );
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->paginate(20)->withQueryString();
        return view('admin.customers', compact('customers'));
    }

    // ── Manajemen Mitra ────────────────────────────────────────
    public function mitras(Request $request)
    {
        $query = User::where('role', 'mitra')->with('mitraProfile')->withCount('ordersAsMitra')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }
        if ($request->filled('vehicle_type')) {
            $query->whereHas('mitraProfile', fn($q) => $q->where('vehicle_type', $request->vehicle_type));
        }

        $mitras = $query->paginate(20)->withQueryString();
        return view('admin.mitras', compact('mitras'));
    }

    // ── Toggle User Status ─────────────────────────────────────
    // BARU
    public function toggleUserStatus(User $user)
    {
        if ($user->is_active) {
            // Suspend
            $user->update([
                'is_active' => false,
                'status' => 'suspended',
            ]);
            // Paksa offline
            $user->mitraProfile?->update(['is_online' => false]);

            return back()->with('success', "Mitra {$user->name} berhasil disuspend.");
        } else {
            // Aktifkan kembali
            $user->update([
                'is_active' => true,
                'status' => 'active',
            ]);

            return back()->with('success', "Mitra {$user->name} berhasil diaktifkan kembali.");
        }
    }

    // ── Setting Tarif ──────────────────────────────────────────
    public function rates()
    {
        $rates = ServiceRate::all()->keyBy('vehicle_type');
        return view('admin.rates', compact('rates'));
    }

    public function updateRates(Request $request)
    {
        $data = $request->validate([
            'motor_rate_per_km' => 'required|numeric|min:100',
            'motor_service_fee' => 'required|numeric|min:0',
            'motor_platform_commission' => 'required|numeric|min:0|max:50',
            'motor_min_fare' => 'required|numeric|min:0',
            'mobil_rate_per_km' => 'required|numeric|min:100',
            'mobil_service_fee' => 'required|numeric|min:0',
            'mobil_platform_commission' => 'required|numeric|min:0|max:50',
            'mobil_min_fare' => 'required|numeric|min:0',
        ]);

        foreach (['motor', 'mobil'] as $type) {
            ServiceRate::updateOrCreate(
                ['vehicle_type' => $type],
                [
                    'rate_per_km' => $data["{$type}_rate_per_km"],
                    'service_fee' => $data["{$type}_service_fee"],
                    'platform_commission' => $data["{$type}_platform_commission"],
                    'min_fare' => $data["{$type}_min_fare"],
                    'updated_by' => Auth::id(),
                ],
            );
        }

        return back()->with('success', 'Tarif berhasil diperbarui!');
    }

    // ── Order Detail & Force Update ────────────────────────────
    public function orderDetail(Order $order)
    {
        $order->load('customer', 'mitra.mitraProfile', 'items', 'trackings.updatedBy');
        return view('admin.order-detail', compact('order'));
    }

    public function forceUpdateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:waiting,accepted,picking_up,in_progress,delivered,completed,cancelled']);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'completed_at' => $newStatus === 'completed' ? now() : $order->completed_at,
            'cancelled_at' => $newStatus === 'cancelled' ? now() : $order->cancelled_at,
        ]);

        // Catat di tracking log
        \App\Models\OrderTracking::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'description' => "Status diubah paksa oleh admin dari {$oldStatus} ke {$newStatus}.",
            'updated_by' => Auth::id(),
        ]);

        // Jika completed, update total_trips & total_earnings mitra
        if ($newStatus === 'completed' && $order->mitra_id) {
            $order->mitra?->mitraProfile?->increment('total_trips');
            $order->mitra?->mitraProfile?->increment('total_earnings', $order->mitra_earning ?? 0);
        }

        return back()->with('success', "Status pesanan #{$order->order_code} berhasil diubah ke " . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    // ── Konfirmasi Pembayaran (QRIS & Transfer) ────────────────
    public function confirmPayment(Order $order)
    {
        // Guard: hanya bisa konfirmasi jika sudah ada bukti dan status pending
        abort_if($order->payment_status !== 'pending' || !$order->payment_proof, 422, 'Pesanan ini tidak dalam status menunggu konfirmasi.');

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'paid_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pembayaran #' . $order->order_code . ' dikonfirmasi!');
    }

    // ── Tolak Bukti Pembayaran ─────────────────────────────────
    // ✅ METHOD BARU — customer diminta upload ulang
    public function rejectPayment(Request $request, Order $order)
    {
        abort_if($order->payment_status !== 'pending', 422, 'Pesanan ini tidak dalam status menunggu konfirmasi.');

        $request->validate(
            [
                'reject_reason' => 'required|string|max:255',
            ],
            [
                'reject_reason.required' => 'Alasan penolakan wajib diisi.',
            ],
        );

        // Hapus bukti yang salah dari storage
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $order->update([
            'payment_proof' => null,
            'payment_status' => 'unpaid', // customer harus upload ulang
        ]);

        return back()->with('success', 'Bukti pembayaran #' . $order->order_code . ' ditolak. Customer diminta upload ulang.');
    }

    // ── Daftar Pembayaran Transfer Menunggu Konfirmasi ─────────
    // ✅ METHOD BARU — khusus transfer (QRIS sudah ada di order detail)
    public function pendingTransfers(Request $request)
    {
        $query = Order::with('customer')->where('payment_method', 'transfer')->where('payment_status', 'pending')->whereNotNull('payment_proof')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('order_code', 'like', "%$s%")->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$s%")));
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.pending-transfers', compact('orders'));
    }

    // ── Verifikasi Mitra ───────────────────────────────────────
    public function verifyMitra(Request $request, User $user)
    {
        abort_if($user->role !== 'mitra', 403);

        $request->validate([
            'status' => 'required|in:verified,rejected,pending',
        ]);

        $user->mitraProfile()->updateOrCreate(['user_id' => $user->id], ['status' => $request->status]);

        $label = match ($request->status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Pending',
        };

        return back()->with('success', "Status mitra {$user->name} berhasil diubah menjadi {$label}.");
    }

    // app/Http/Controllers/AdminController.php

    public function suspendMitra(User $user)
    {
        if ($user->isSuspended()) {
            return redirect()->back()->with('error', 'Mitra sudah dalam status suspended.');
        }

        $user->update([
            'status' => 'suspended',
            'is_active' => false,
        ]);

        return redirect()
            ->back()
            ->with('success', "Mitra {$user->name} berhasil disuspend.");
    }

    public function unsuspendMitra(User $user)
    {
        $user->update([
            'status' => 'active',
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', "Suspend mitra {$user->name} berhasil dicabut.");
    }

    // AdminBonusController::processAll()
// ── Proses Bonus Performa ──────────────────────────────────
// ── Bonus Index ────────────────────────────────────────────
public function bonusIndex()
{
    $rates = BonusRate::orderBy('min_orders')->get();

    $mitraRecap = User::where('role', 'mitra')
        ->where('is_active', true)
        ->get()
        ->map(function ($user) {
            $bonus = app(BonusService::class)->calculate($user, 'week');
            return (object) array_merge($bonus, [
                'name' => $user->name,
                'id'   => $user->id,
            ]);
        })
        ->sortByDesc('order_count')
        ->values();

    $mitras = User::where('role', 'mitra')->orderBy('name')->get();

    $history = MitraEarning::with('mitra')
        ->whereIn('type', ['bonus', 'manual'])
        ->latest('earned_date')
        ->paginate(15);

    return view('admin.bonus', compact('rates', 'mitraRecap', 'mitras', 'history'));
}

// ── Bonus Update Tier ──────────────────────────────────────
public function bonusUpdate(Request $request, BonusRate $rate)
{
    $request->validate([
        'bonus_amount' => 'required|integer|min:0',
        'rating_bonus' => 'required|integer|min:0',
        'min_orders'   => 'required|integer|min:0',
        'max_orders'   => 'nullable|integer|min:0',
    ]);

    $rate->update([
        'bonus_amount' => $request->bonus_amount,
        'rating_bonus' => $request->rating_bonus,
        'min_orders'   => $request->min_orders,
        'max_orders'   => $request->max_orders ?: null,
        'is_active'    => $request->boolean('is_active'),
    ]);

    return back()->with('success', 'Konfigurasi ' . ucfirst($rate->tier_name) . ' berhasil disimpan.');
}

// ── Bonus Manual ───────────────────────────────────────────
public function bonusManual(Request $request)
{
    $request->validate([
        'mitra_id' => 'required|exists:users,id',
        'amount'   => 'required|integer|min:1',
        'period'   => 'required|in:today,week,month',
        'note'     => 'nullable|string|max:100',
    ]);

    MitraEarning::create([
        'mitra_id'     => $request->mitra_id,
        'type'         => 'manual',
        'bonus'        => $request->amount,
        'net_amount'   => 0,
        'gross_amount' => 0,
        'platform_fee' => 0,
        'note'         => $request->note,
        'period'       => $request->period,
        'earned_date'  => now(),
    ]);

    return back()->with('success', 'Bonus manual berhasil ditambahkan.');
}

// ── Proses Bonus Performa ──────────────────────────────────
public function processAll(Request $request)
{
    $period = $request->get('period', 'week');
    $results = [];

    User::where('role', 'mitra')
        ->where('is_active', true)
        ->each(function ($user) use ($period, &$results) {
            $bonus = app(BonusService::class)->calculate($user, $period);
            $results[] = [
                'mitra'       => $user->name,
                'order_count' => $bonus['order_count'],
                'tier'        => $bonus['tier'],
                'total_bonus' => $bonus['total_bonus'],
            ];
        });

  
}
}
