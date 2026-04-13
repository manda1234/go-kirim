<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\ServiceRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function dashboard()
    {
        date_default_timezone_set('Asia/Jakarta');

        $user = Auth::user();
        $activeOrders = Order::where('customer_id', $user->id)->active()->latest()->take(3)->get();
        $recentOrders = Order::where('customer_id', $user->id)->latest()->take(5)->get();
        $totalOrders = Order::where('customer_id', $user->id)->count();
        $totalSpend = Order::where('customer_id', $user->id)->where('status', 'completed')->sum('total_fare');

        return view('customer.dashboard', compact('user', 'activeOrders', 'recentOrders', 'totalOrders', 'totalSpend'));
    }

    // ── Order Form ─────────────────────────────────────────────
    public function orderForm(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $serviceType = $request->get('type', 'kirim_barang');

        try {
            $rates = ServiceRate::where('is_active', true)->get()->keyBy('vehicle_type');
        } catch (\Exception $e) {
            $rates = collect([
                'motor' => (object) ['price_per_km' => 2500, 'service_fee' => 2000],
                'mobil' => (object) ['price_per_km' => 4000, 'service_fee' => 2000],
            ]);
        }

        $addresses = [];

        return view('customer.order-form', compact('serviceType', 'rates', 'addresses'));
    }

    // ── Estimate Price (AJAX) ──────────────────────────────────
    public function estimatePrice(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $request->validate([
            'vehicle_type' => 'required|in:motor,mobil',
            'distance_km' => 'required|numeric|min:0.1',
        ]);

        $distanceKm = (float) $request->distance_km;

        try {
            $rate = ServiceRate::forVehicle($request->vehicle_type);
            if ($rate) {
                return response()->json($rate->calculateFare($distanceKm));
            }
        } catch (\Exception $e) {
            // fallback ke manual
        }

        return response()->json($this->calculateFare($request->vehicle_type, $distanceKm));
    }

    // ── Store Order ────────────────────────────────────────────
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $validated = $request->validate([
            'service_type' => 'required|in:kirim_barang,antar_orang,food_delivery',
            'vehicle_type' => 'required|in:motor,mobil',
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'destination_lat' => 'nullable|numeric',
            'destination_lng' => 'nullable|numeric',
            'item_type' => 'nullable|string',
            'item_weight' => 'nullable|numeric',
            'notes' => 'nullable|string|max:500',
            'distance_km' => 'required|numeric|min:0.1',
            'payment_method' => 'required|in:cash,transfer,e_wallet,qris',
            'passengers' => 'nullable|integer|min:1|max:10',
            'food_items' => 'nullable|array',
            'food_items.*.name' => 'required_with:food_items|string',
            'food_items.*.price' => 'required_with:food_items|numeric',
            'food_items.*.qty' => 'required_with:food_items|integer|min:1',
        ]);

        // ✅ Hitung fare lengkap
        $fare = $this->calculateFare($validated['vehicle_type'], (float) $validated['distance_km']);

        // Override jika ServiceRate model tersedia
        try {
            $rate = ServiceRate::forVehicle($validated['vehicle_type']);
            if ($rate) {
                $fareData = $rate->calculateFare((float) $validated['distance_km']);
                foreach ($fareData as $key => $value) {
                    if ($value !== null && array_key_exists($key, $fare)) {
                        $fare[$key] = $value;
                    }
                }
            }
        } catch (\Exception $e) {
            // pakai kalkulasi manual
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_code' => Order::generateCode(),
                'customer_id' => Auth::id(),
                'service_type' => $validated['service_type'],
                'vehicle_type' => $validated['vehicle_type'],
                'pickup_address' => $validated['pickup_address'],
                'pickup_lat' => $validated['pickup_lat'] ?? null,
                'pickup_lng' => $validated['pickup_lng'] ?? null,
                'destination_address' => $validated['destination_address'],
                'destination_lat' => $validated['destination_lat'] ?? null,
                'destination_lng' => $validated['destination_lng'] ?? null,
                'item_type' => $validated['item_type'] ?? null,
                'item_weight' => $validated['item_weight'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'distance_km' => $fare['distance_km'],
                'distance_fare' => $fare['distance_fare'],
                'base_fare' => $fare['base_fare'],
                'service_fee' => $fare['service_fee'],
                'total_fare' => $fare['total_fare'],
                'platform_fee' => $fare['platform_fee'], // ✅ platform 20%
                'mitra_earning' => $fare['mitra_earning'], // ✅ mitra 80%
                'status' => 'waiting',
            ]);

            // Food items
            if (!empty($validated['food_items'])) {
                foreach ($validated['food_items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_name' => $item['name'],
                        'quantity' => $item['qty'],
                        'unit_price' => $item['price'],
                        'subtotal' => $item['price'] * $item['qty'],
                    ]);
                }
            }

            // ✅ PERBAIKAN: Deskripsi tracking awal disesuaikan per tipe layanan
            $trackingDesc = match($validated['service_type']) {
                'antar_orang'   => 'Pesanan dibuat, mencari driver terdekat.',
                'food_delivery' => 'Pesanan dibuat, mencari kurir makanan.',
                default         => 'Pesanan dibuat, mencari mitra terdekat.',
            };

            OrderTracking::create([
                'order_id' => $order->id,
                'status' => 'waiting',
                'description' => $trackingDesc,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order' => $order->load('trackings'),
                ]);
            }

            return redirect()
                ->route('customer.tracking', ['order_id' => $order->id])
                ->with('success', 'Pesanan #' . $order->order_code . ' berhasil dibuat! Mencari mitra terdekat...');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal membuat pesanan: ' . $e->getMessage()]);
        }
    }

    // ── Tracking ───────────────────────────────────────────────
    public function tracking(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $user = Auth::user();
        $activeOrders = Order::where('customer_id', $user->id)->active()->with('mitra.mitraProfile', 'trackings')->latest()->get();
        $historyOrders = Order::where('customer_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(10);

        $selected = null;
        if ($request->has('order_id')) {
            $selected = Order::where('customer_id', $user->id)->with('trackings', 'mitra.mitraProfile', 'items')->findOrFail($request->order_id);
        } elseif ($activeOrders->isNotEmpty()) {
            $selected = $activeOrders->first()->load('trackings', 'mitra.mitraProfile', 'items');
        }

        return view('customer.tracking', compact('activeOrders', 'historyOrders', 'selected'));
    }

    // ── Order Detail ───────────────────────────────────────────
    public function show(Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');

        abort_if($order->customer_id !== Auth::id(), 403);
        $order->load('trackings', 'mitra.mitraProfile', 'items');
        return view('customer.order-detail', compact('order'));
    }

    // ── Rate Order ─────────────────────────────────────────────
    public function rate(Request $request, Order $order)
    {
        abort_if($order->customer_id !== Auth::id(), 403);
        abort_if($order->status !== 'completed', 422);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $order->update([
            'customer_rating' => $request->rating,
            'customer_review' => $request->review,
        ]);

        if ($order->mitra) {
            $avg = Order::where('mitra_id', $order->mitra_id)->whereNotNull('customer_rating')->avg('customer_rating');
            $order->mitra->mitraProfile?->update(['rating' => round($avg, 2)]);
        }

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    // ── Helper: Hitung Fare ────────────────────────────────────
    private function calculateFare(string $vehicleType, float $distanceKm): array
    {
        $ratePerKm = $vehicleType === 'motor' ? 2500 : 4000;
        $serviceFee = 2000;
        $distanceFare = round($distanceKm * $ratePerKm);
        $baseFare = $distanceFare;
        $totalFare = $baseFare + $serviceFee;

        // Platform 20%, mitra 80%
        $platformFee = round($baseFare * 0.2);
        $mitraEarning = $baseFare - $platformFee;

        return [
            'distance_km' => $distanceKm,
            'distance_fare' => $distanceFare,
            'base_fare' => $baseFare,
            'service_fee' => $serviceFee,
            'total_fare' => $totalFare,
            'platform_fee' => $platformFee,
            'mitra_earning' => $mitraEarning,
        ];
    }

    public function cancelOrder(Request $request, Order $order)
    {
        // ── 1. Pastikan order milik customer yang login ────────────
        abort_if($order->customer_id !== auth()->id(), 403);

        // ── 2. Validasi alasan pembatalan ─────────────────────────
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
        ]);

        // ── 3. Cek apakah order masih bisa dibatalkan ─────────────
        $cancellableStatuses = ['waiting', 'accepted'];

        if (!in_array($order->status, $cancellableStatuses)) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah berstatus "' . $order->status_badge['label'] . '".');
        }

        // ── 4. Update status order ────────────────────────────────
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
            'cancelled_by' => 'customer',
            'cancelled_at' => now(),
        ]);

        // ── 5. Simpan ke tabel order_trackings ────────────────────
        $order->trackings()->create([
            'status' => 'cancelled',
            'description' => 'Pesanan dibatalkan oleh customer. Alasan: ' . $request->cancel_reason,
            'updated_by' => auth()->id(),
        ]);

        // ── 6. Redirect dengan pesan sukses ───────────────────────
        return redirect()
            ->route('customer.tracking')
            ->with('success', 'Pesanan ' . $order->order_code . ' berhasil dibatalkan.');
    }
}