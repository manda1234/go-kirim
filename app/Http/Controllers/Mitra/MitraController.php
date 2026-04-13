<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\MitraEarning;
use App\Services\BonusService;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        date_default_timezone_set('Asia/Jakarta');

        $user    = Auth::user();
        $profile = $user->mitraProfile;

        $activeOrder  = Order::where('mitra_id', $user->id)->active()->with('customer', 'trackings')->latest()->first();
        $activeOrders = Order::where('mitra_id', $user->id)->active()->with('customer', 'trackings')->latest()->get();

        // ── Blokir order waiting jika suspended ────────────────
        if (!$user->is_active || $user->status === 'suspended') {
            $pendingOrders = collect();
            $waitingOrders = collect();
        } else {
            $pendingOrders = Order::where('status', 'waiting')
                ->whereNull('mitra_id')
                ->where('vehicle_type', $user->vehicle_type)
                ->with('customer')
                ->latest()
                ->take(10)
                ->get();

            $waitingOrders = $pendingOrders;
        }

        $historyOrders = Order::where('mitra_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(10);

        $recentOrders = Order::where('mitra_id', $user->id)->latest()->take(6)->get();

        $totalCompleted = Order::where('mitra_id', $user->id)->where('status', 'completed')->count();
        $totalEarning   = Order::where('mitra_id', $user->id)->where('status', 'completed')->sum('mitra_earning');
        $todayOrders    = Order::where('mitra_id', $user->id)->whereDate('created_at', today())->count();
        $todayEarning   = Order::where('mitra_id', $user->id)->where('status', 'completed')->whereDate('completed_at', today())->sum('mitra_earning');
        $weeklyEarning  = Order::where('mitra_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('mitra_earning');

        $ratingStats = Order::where('mitra_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('customer_rating')
            ->selectRaw('ROUND(AVG(customer_rating), 1) as avg_rating, COUNT(*) as total_reviews')
            ->first();

        return view('mitra.dashboard', compact(
            'user', 'profile',
            'activeOrder', 'activeOrders',
            'pendingOrders', 'waitingOrders',
            'historyOrders', 'recentOrders',
            'totalCompleted', 'totalEarning',
            'todayOrders', 'todayEarning', 'weeklyEarning',
            'ratingStats',
        ));
    }

    // ── Halaman Rating & Ulasan Mitra ──────────────────────────
    public function ratings()
    {
        $mitraId = Auth::id();

        $reviews = Order::with('customer:id,name')
            ->where('mitra_id', $mitraId)
            ->where('status', 'completed')
            ->whereNotNull('customer_rating')
            ->orderByDesc('completed_at')
            ->paginate(10);

        $stats = Order::where('mitra_id', $mitraId)
            ->where('status', 'completed')
            ->whereNotNull('customer_rating')
            ->selectRaw('
                COUNT(*)                         AS total_reviews,
                ROUND(AVG(customer_rating), 1)   AS avg_rating,
                SUM(customer_rating = 5)         AS star5,
                SUM(customer_rating = 4)         AS star4,
                SUM(customer_rating = 3)         AS star3,
                SUM(customer_rating = 2)         AS star2,
                SUM(customer_rating = 1)         AS star1
            ')
            ->first();

        return view('mitra.ratings', compact('reviews', 'stats'));
    }

    // ── Toggle Online ──────────────────────────────────────────
    public function toggleOnline()
    {
        $user    = Auth::user();
        $profile = $user->mitraProfile;

        // Blokir jika suspended
        if (!$user->is_active || $user->status === 'suspended') {
            return response()->json([
                'is_online' => false,
                'suspended' => true,
                'message'   => 'Akun Anda sedang disuspend. Tidak dapat mengubah status online.',
            ], 403);
        }

        $profile->update(['is_online' => !$profile->is_online]);

        return response()->json([
            'is_online' => $profile->is_online,
            'suspended' => false,
        ]);
    }

    // ── Update Status (tombol bertahap) ────────────────────────
    public function updateStatus(Request $request, Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Blokir jika suspended
        if (!Auth::user()->is_active || Auth::user()->status === 'suspended') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda sedang disuspend. Tidak dapat memproses order.',
                ], 403);
            }
            return back()->with('error', '🚫 Akun Anda sedang disuspend. Tidak dapat memproses order.');
        }

        if ($order->status !== 'waiting' && $order->mitra_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak mengubah pesanan ini.');
        }

        $next = $order->getNextStatus();

        if (!$next) {
            return back()->with('error', 'Tidak ada status berikutnya untuk pesanan ini.');
        }

        $updateData = ['status' => $next['status']];

        if (!empty($next['timestamp'])) {
            $updateData[$next['timestamp']] = now();
        }

        if ($next['status'] === 'accepted') {
            $updateData['mitra_id']      = Auth::id();
            $updateData['mitra_earning'] = round($order->total_fare * 0.9, 2);
            $updateData['platform_fee']  = round($order->total_fare * 0.1, 2);
        }

        $order->update($updateData);

        // ── TAMBAHAN: Buat MitraEarning otomatis saat completed ──
        if ($next['status'] === 'completed') {
            $order->refresh(); // pastikan mitra_earning & platform_fee sudah tersimpan
            MitraEarning::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'mitra_id'     => $order->mitra_id,
                     'type'         => 'order', 
                    'gross_amount' => $order->total_fare ?? 0,
                    'platform_fee' => $order->platform_fee ?? round($order->total_fare * 0.1, 2),
                    'net_amount'   => $order->mitra_earning ?? round($order->total_fare * 0.9, 2),
                    'bonus'        => 0,
                    'earned_date'  => now(),
                ]
            );
        }

        OrderTracking::create([
            'order_id'    => $order->id,
            'status'      => $next['status'],
            'description' => OrderTracking::descriptionFor($next['status']),
            'updated_by'  => Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'new_status' => $next['status'],
                'label'      => $order->fresh()->status_badge['label'],
            ]);
        }

        return back()->with('success', 'Status diperbarui: ' . $order->fresh()->status_badge['label']);
    }

    // ── Accept Order ───────────────────────────────────────────
    public function acceptOrder(Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Blokir jika suspended
        if (!Auth::user()->is_active || Auth::user()->status === 'suspended') {
            return back()->with('error', '🚫 Akun Anda sedang disuspend. Tidak dapat menerima order.');
        }

        if ($order->vehicle_type !== Auth::user()->vehicle_type) {
            return back()->with('error', 'Tipe kendaraan tidak sesuai.');
        }

        if ($order->status !== 'waiting' || $order->mitra_id !== null) {
            return back()->with('error', 'Pesanan sudah diambil mitra lain.');
        }

        $order->update([
            'status'        => 'accepted',
            'mitra_id'      => Auth::id(),
            'accepted_at'   => now(),
            'mitra_earning' => round($order->total_fare * 0.9, 2),
            'platform_fee'  => round($order->total_fare * 0.1, 2),
        ]);

        OrderTracking::create([
            'order_id'    => $order->id,
            'status'      => 'accepted',
            'description' => OrderTracking::descriptionFor('accepted'),
            'updated_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Pesanan berhasil diterima!');
    }

    // ── Cancel Order ───────────────────────────────────────────
    public function cancelOrder(Request $request, Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');

        abort_if($order->mitra_id !== Auth::id(), 403);

        $request->validate(['cancel_reason' => 'required|string|max:255']);

        if (!$order->isCancellable()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $order->update([
            'status'        => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
            'cancelled_by'  => 'mitra',
            'cancelled_at'  => now(),
        ]);

        OrderTracking::create([
            'order_id'    => $order->id,
            'status'      => 'cancelled',
            'description' => 'Pesanan dibatalkan oleh mitra. Alasan: ' . $request->cancel_reason,
            'updated_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    // ── Profile ────────────────────────────────────────────────
    public function profile()
    {
        return view('mitra.profile');
    }

    // ── Pendapatan ─────────────────────────────────────────────
public function earnings(Request $request)
{
    $user   = Auth::user();
    $period = $request->get('period', 'week');

    [$start, $end] = match ($period) {
        'today' => [today()->startOfDay(),  today()->endOfDay()],
        'month' => [now()->startOfMonth(),  now()->endOfMonth()],
        default => [now()->startOfWeek(),   now()->endOfWeek()],
    };

    $query = MitraEarning::where('mitra_id', $user->id)
                          ->whereBetween('earned_date', [$start, $end])
                          ->with('order');

    $list        = $query->latest('earned_date')->paginate(20);
    $totalNet    = $list->sum('net_amount') + $list->sum('bonus');
    $totalOrders = MitraEarning::where('mitra_id', $user->id)
                                ->where('type', 'order')
                                ->whereBetween('earned_date', [$start, $end])
                                ->count();

    $bonus = app(BonusService::class)->calculate($user, $period);

    return view('mitra.earnings', compact(
        'list', 'totalNet', 'totalOrders', 'period', 'bonus'
    ));
}
}