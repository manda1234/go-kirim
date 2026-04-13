@extends('layouts.app')
@section('title', 'Detail Transaksi #' . $order->order_code)

@section('sidebar-nav')
  <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
  <a href="{{ route('admin.transactions') }}" class="sidebar-link"><i class="fas fa-receipt w-5"></i> Transaksi</a>
  <a href="{{ route('admin.customers') }}" class="sidebar-link"><i class="fas fa-users w-5"></i> Customer</a>
  <a href="{{ route('admin.mitras') }}" class="sidebar-link"><i class="fas fa-motorcycle w-5"></i> Mitra</a>
  <a href="{{ route('admin.bonus.index') }}" class="sidebar-link "><i class="fas fa-trophy w-5"></i> Bonus Performa</a>
  <a href="{{ route('admin.rates') }}" class="sidebar-link"><i class="fas fa-cog w-5"></i> Setting Tarif</a>
  <a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="fas fa-qrcode w-5"></i> Setting QRIS</a>
@endsection
@push('styles')
    <style>
        .info-row {
            display: flex;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            gap: 12px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            min-width: 120px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding-top: 2px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
        }

        .timeline-item {
            position: relative;
            padding-left: 32px;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 20px;
            width: 2px;
            bottom: 0;
            background: #e2e8f0;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            border: 2px solid white;
        }

        .timeline-dot.done {
            background: #16a34a;
            box-shadow: 0 0 0 2px #bbf7d0;
            color: white;
        }

        .timeline-dot.active {
            background: #3b82f6;
            box-shadow: 0 0 0 2px #bfdbfe;
            color: white;
        }

        .timeline-dot.pending {
            background: #f1f5f9;
            box-shadow: 0 0 0 2px #e2e8f0;
            color: #94a3b8;
        }

        .timeline-dot.cancelled {
            background: #ef4444;
            box-shadow: 0 0 0 2px #fecaca;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-3xl mx-auto space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.transactions') }}"
                class="w-9 h-9 bg-white rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition flex-shrink-0">
                <i class="fas fa-arrow-left text-slate-600 text-sm"></i>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="font-black text-lg text-slate-800">Detail Transaksi</h1>
                <p class="text-xs text-slate-400 font-medium">#{{ $order->order_code }}</p>
            </div>
            <span class="badge badge-{{ $order->status }} flex-shrink-0">
                {{ $order->status_badge['label'] ?? ucfirst($order->status) }}
            </span>
        </div>

        {{-- GRID 2 KOLOM (desktop) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- KOLOM KIRI --}}
            <div class="space-y-4">

                {{-- INFO PESANAN --}}
                <div class="card p-5">
                    <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-clipboard-list" style="color:#16a34a"></i> Info Pesanan
                    </h2>
                    <div>
                        <div class="info-row">
                            <span class="info-label">Kode</span>
                            <span class="info-value font-black" style="color:#16a34a">#{{ $order->order_code }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Layanan</span>
                            <span class="info-value">
                                @php
                                    $svcIcon = match ($order->service_type) {
                                        'kirim_barang' => '📦',
                                        'antar_orang' => '🛵',
                                        'food_delivery' => '🍜',
                                        default => '📋',
                                    };
                                    $svcLabel = match ($order->service_type) {
                                        'kirim_barang' => 'Kirim Barang',
                                        'antar_orang' => 'Antar Orang',
                                        'food_delivery' => 'Food Delivery',
                                        default => $order->service_type,
                                    };
                                @endphp
                                {{ $svcIcon }} {{ $svcLabel }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kendaraan</span>
                            <span
                                class="info-value">{{ $order->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Pembayaran</span>
                            <span class="info-value capitalize">
                                {{ str_replace('_', ' ', ucfirst($order->payment_method)) }}
                                {{-- ✅ SESUDAH --}}
                                @php
                                    $isCash = $order->payment_method === 'cash';
                                    $isPaid = $isCash
                                        ? $order->status === 'completed'
                                        : $order->payment_status === 'paid';
                                @endphp

                                <span class="badge badge-{{ $isPaid ? 'success' : ($isCash ? 'info' : 'warning') }} ml-1"
                                    style="font-size:10px">
                                    @if ($isCash)
                                        @if ($order->status === 'completed')
                                            ✅ Lunas (Cash)
                                        @elseif($order->status === 'cancelled')
                                            ❌ Batal
                                        @else
                                            🪙 Bayar di Tempat
                                        @endif
                                    @else
                                        {{ $isPaid ? '✅ Lunas' : '⏳ Belum Bayar' }}
                                    @endif
                                </span>

                               
                            </span>
                        </div>
                        @if ($order->item_type)
                            <div class="info-row">
                                <span class="info-label">Jenis Barang</span>
                                <span class="info-value capitalize">{{ $order->item_type }}</span>
                            </div>
                        @endif
                        @if ($order->item_weight)
                            <div class="info-row">
                                <span class="info-label">Berat</span>
                                <span class="info-value">{{ $order->item_weight }} kg</span>
                            </div>
                        @endif
                        @if ($order->notes)
                            <div class="info-row">
                                <span class="info-label">Catatan</span>
                                <span class="info-value text-slate-500 italic text-sm">{{ $order->notes }}</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Dibuat</span>
                            <span class="info-value text-sm">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        @if ($order->completed_at)
                            <div class="info-row">
                                <span class="info-label">Selesai</span>
                                <span
                                    class="info-value text-sm">{{ \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                        @if ($order->cancelled_at)
                            <div class="info-row">
                                <span class="info-label">Dibatalkan</span>
                                <span
                                    class="info-value text-sm text-red-500">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                        @if ($order->cancel_reason)
                            <div class="info-row">
                                <span class="info-label">Alasan Batal</span>
                                <span class="info-value text-sm text-red-500">{{ $order->cancel_reason }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RUTE --}}
                <div class="card p-5">
                    <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-route" style="color:#16a34a"></i> Rute
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                style="background:#dcfce7">
                                <span class="w-2.5 h-2.5 rounded-full" style="background:#16a34a"></span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pickup</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $order->pickup_address }}</p>
                                @if ($order->pickup_lat && $order->pickup_lng)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $order->pickup_lat }},
                                        {{ $order->pickup_lng }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3 px-1">
                            <div class="w-5 flex justify-center">
                                <div class="w-0.5 h-6 border-l-2 border-dashed border-slate-200"></div>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">
                                <i class="fas fa-ruler-horizontal mr-1"></i>{{ $order->distance_km ?? '–' }} km
                            </span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                style="background:#fee2e2">
                                <span class="w-2.5 h-2.5 rounded-full" style="background:#ef4444"></span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Tujuan</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $order->destination_address }}
                                </p>
                                @if ($order->destination_lat && $order->destination_lng)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $order->destination_lat }},
                                        {{ $order->destination_lng }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN HARGA --}}
                <div class="card p-5">
                    <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-receipt" style="color:#16a34a"></i> Rincian Harga
                    </h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Ongkos Kirim</span>
                            <span class="font-semibold">Rp {{ number_format($order->base_fare ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Biaya Layanan</span>
                            <span class="font-semibold">Rp
                                {{ number_format($order->service_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-slate-100 pt-2 flex justify-between font-black">
                            <span>Total</span>
                            <span style="color:#16a34a">Rp
                                {{ number_format($order->total_fare ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-dashed border-slate-100 pt-2 space-y-1.5">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Platform Fee (20%)</span>
                                <span class="font-bold text-slate-600">Rp
                                    {{ number_format($order->platform_fee ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-400">Pendapatan Mitra (80%)</span>
                                <span class="font-bold" style="color:#16a34a">Rp
                                    {{ number_format($order->mitra_earning ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PEMBAYARAN QRIS --}}
                {{-- ✅ SESUDAH --}}
                @if (in_array($order->payment_method, ['qris', 'transfer']))
                    <div class="card p-5">
                        <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                            <i class="fas fa-qrcode" style="color:#16a34a"></i> Pembayaran QRIS
                        </h2>

                        @if ($order->isPaid())
                            <div class="flex items-center gap-3 p-3 rounded-xl"
                                style="background:#f0fdf4;border:1px solid #bbf7d0">
                                <i class="fas fa-check-circle" style="color:#16a34a"></i>
                                <div>
                                    <p class="font-bold text-sm" style="color:#15803d">Lunas</p>
                                    <p class="text-xs" style="color:#16a34a">Dikonfirmasi
                                        {{ $order->paid_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        @elseif($order->payment_proof)
                            <div class="space-y-3">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Bukti Transfer Customer
                                </p>
                                <img src="{{ $order->payment_proof_url }}"
                                    class="w-full max-w-xs rounded-xl border border-slate-200">

                                <div class="flex items-center gap-2 p-3 rounded-xl text-sm font-semibold"
                                    style="background:#fffbeb;border:1px solid #fde68a;color:#b45309">
                                    <i class="fas fa-clock"></i> Menunggu konfirmasi pembayaran
                                </div>

                                <form method="POST" action="{{ route('admin.orders.confirm-payment', $order) }}"
                                    onsubmit="return confirm('Konfirmasi pembayaran order #{{ $order->order_code }}?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-2.5 font-bold rounded-xl text-sm text-white transition-all active:scale-95 flex items-center justify-center gap-2"
                                        style="background:#16a34a">
                                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="p-3 rounded-xl text-sm font-semibold"
                                style="background:#f8fafc;border:1px solid #e2e8f0;color:#94a3b8">
                                <i class="fas fa-hourglass-half mr-1"></i> Menunggu customer upload bukti transfer
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-4">

                {{-- INFO CUSTOMER --}}
                <div class="card p-5">
                    <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-user" style="color:#16a34a"></i> Customer
                    </h2>
                    @if ($order->customer)
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center font-black text-lg flex-shrink-0"
                                style="background:#dbeafe;color:#1d4ed8">
                                {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-slate-800">{{ $order->customer->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $order->customer->email }}</p>
                                <p class="text-xs text-slate-400">{{ $order->customer->phone ?? '–' }}</p>
                            </div>
                        </div>
                        @if ($order->customer_rating)
                            <div class="mt-3 pt-3 border-t border-slate-100">
                                <p class="text-xs text-slate-400 mb-1">Rating dari customer:</p>
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star text-sm {{ $i <= $order->customer_rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                    @endfor
                                    <span
                                        class="text-xs font-bold text-slate-600 ml-1">{{ $order->customer_rating }}/5</span>
                                </div>
                                @if ($order->customer_review)
                                    <p class="text-xs text-slate-500 italic mt-1">"{{ $order->customer_review }}"</p>
                                @endif
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-slate-400">Customer tidak ditemukan</p>
                    @endif
                </div>

                {{-- INFO MITRA --}}
                <div class="card p-5">
                    <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                        <i class="fas fa-motorcycle" style="color:#16a34a"></i> Mitra
                    </h2>
                    @if ($order->mitra)
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center font-black text-lg flex-shrink-0"
                                style="background:#dcfce7;color:#16a34a">
                                {{ strtoupper(substr($order->mitra->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-slate-800">{{ $order->mitra->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $order->mitra->email }}</p>
                                <p class="text-xs text-slate-400">{{ $order->mitra->phone ?? '–' }}</p>
                            </div>
                        </div>
                        @if ($order->mitra->mitraProfile)
                            <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400">Kendaraan</span>
                                    <p class="font-bold text-slate-700 mt-0.5">
                                        {{ $order->mitra->mitraProfile->vehicle_brand ?? '–' }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-400">Plat Nomor</span>
                                    <p class="font-bold text-slate-700 mt-0.5">
                                        {{ $order->mitra->mitraProfile->vehicle_plate ?? '–' }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-400">Rating</span>
                                    <p class="font-bold mt-0.5" style="color:#f59e0b">⭐
                                        {{ $order->mitra->mitraProfile->rating ?? '5.0' }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-400">Total Trip</span>
                                    <p class="font-bold text-slate-700 mt-0.5">
                                        {{ $order->mitra->mitraProfile->total_trips ?? 0 }}x</p>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-2xl mb-1">⏳</p>
                            <p class="text-sm text-slate-400">Belum ada mitra</p>
                        </div>
                    @endif
                </div>

                {{-- FOOD ITEMS --}}
                @if ($order->items && $order->items->isNotEmpty())
                    <div class="card p-5">
                        <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                            🍜 Item Makanan
                        </h2>
                        <div class="space-y-2">
                            @foreach ($order->items as $item)
                                <div
                                    class="flex justify-between items-center py-1.5 border-b border-slate-50 last:border-0">
                                    <div>
                                        <p class="font-semibold text-sm text-slate-800">{{ $item->item_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->quantity }}x · Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-bold text-sm text-slate-800">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- FORCE UPDATE STATUS (Admin) --}}
                @if (!in_array($order->status, ['completed', 'cancelled']))
                    <div class="card p-5">
                        <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm">
                            <i class="fas fa-tools" style="color:#f59e0b"></i> Force Update Status
                        </h2>
                        <p class="text-xs text-slate-400 mb-3">Admin dapat mengubah status pesanan secara paksa jika
                            diperlukan.</p>
                        <form method="POST" action="{{ route('admin.order.update', $order->id) }}"
                            onsubmit="return confirm('Yakin ubah status pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-2">
                                <select name="status" class="form-select flex-1 text-sm">
                                    @foreach (['waiting', 'accepted', 'picking_up', 'in_progress', 'delivered', 'completed', 'cancelled'] as $s)
                                        <option value="{{ $s }}"
                                            {{ $order->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 text-white font-bold rounded-xl text-sm transition-all active:scale-95 flex-shrink-0"
                                    style="background:#f59e0b">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        </div>

     {{-- TIMELINE STATUS (full width) --}}
<div class="card p-5">
    <h2 class="font-black text-slate-800 mb-5 flex items-center gap-2 text-sm">
        <i class="fas fa-history" style="color:#16a34a"></i> Riwayat Status
    </h2>

    @php
        // 1. Definisikan Steps berdasarkan service_type (Logika dari Kode 1)
        if ($order->service_type === 'kirim_barang') {
            $steps = [
                ['status' => 'waiting',     'icon' => 'fa-box',            'title' => 'Pesanan Dibuat'],
                ['status' => 'accepted',    'icon' => 'fa-check-circle',   'title' => 'Mitra Menerima'],
                ['status' => 'picking_up',   'icon' => 'fa-truck-loading',  'title' => 'Ambil Barang'],
                ['status' => 'in_progress',  'icon' => 'fa-motorcycle',     'title' => 'Dalam Perjalanan'],
                ['status' => 'completed',    'icon' => 'fa-check-double',   'title' => 'Selesai']
            ];
        } elseif ($order->service_type === 'antar_orang') {
            $steps = [
                ['status' => 'waiting',     'icon' => 'fa-clock',          'title' => 'Mencari Driver'],
                ['status' => 'accepted',    'icon' => 'fa-user-check',     'title' => 'Driver Menerima'],
                ['status' => 'on_the_way',   'icon' => 'fa-motorcycle',     'title' => 'Menuju Lokasi'],
                ['status' => 'picked_up',    'icon' => 'fa-user-friends',   'title' => 'Dijemput'],
                ['status' => 'arrived',      'icon' => 'fa-map-marker-alt', 'title' => 'Sampai Tujuan'],
                ['status' => 'completed',    'icon' => 'fa-flag-checkered', 'title' => 'Perjalanan Selesai']
            ];
        } else { // Default / Makanan
            $steps = [
                ['status' => 'waiting',     'icon' => 'fa-utensils',       'title' => 'Pesanan Masuk'],
                ['status' => 'accepted',    'icon' => 'fa-check',          'title' => 'Kurir Menerima'],
                ['status' => 'picking_up',   'icon' => 'fa-store',          'title' => 'Ambil Pesanan'],
                ['status' => 'in_progress',  'icon' => 'fa-shipping-fast',  'title' => 'Sedang Diantar'],
                ['status' => 'completed',    'icon' => 'fa-grin-beam',      'title' => 'Pesanan Tiba']
            ];
        }

        // 2. Buat array flat untuk mapping urutan status
        $statusOrder = array_column($steps, 'status');
        $currentIndex = array_search($order->status, $statusOrder);
        $trackingMap = $order->trackings->keyBy('status');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8">
        @if ($order->status === 'cancelled')
            <div class="timeline-item">
                <div class="timeline-dot cancelled"><i class="fas fa-times" style="font-size:8px"></i></div>
                <div>
                    <p class="font-bold text-sm text-red-600">Pesanan Dibatalkan</p>
                    @if ($order->cancel_reason)
                        <p class="text-xs text-slate-400 mt-0.5">{{ $order->cancel_reason }}</p>
                    @endif
                    @if ($order->cancelled_at)
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, H:i') }}
                        </p>
                    @endif
                </div>
            </div>
        @else
            @foreach ($steps as $idx => $step)
                @php
                    $statusKey = $step['status'];
                    $isDone = $currentIndex !== false && $idx <= $currentIndex;
                    $isActive = $statusKey === $order->status;
                    $tracking = $trackingMap[$statusKey] ?? null;
                    $dotClass = $isDone ? ($isActive ? 'active' : 'done') : 'pending';
                @endphp
                
                <div class="timeline-item">
                    <div class="timeline-dot {{ $dotClass }}">
                        <i class="fas {{ $step['icon'] }}" style="font-size:8px"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm {{ $isDone ? 'text-slate-800' : 'text-slate-400' }}">
                            {{ $step['title'] }}
                            @if($isActive)
                                <span class="ml-2 text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Sekarang</span>
                            @endif
                        </p>
                        
                        @if ($tracking)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $tracking->description }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $tracking->created_at->format('d M Y, H:i') }}
                            </p>
                        @elseif(!$isDone)
                            <p class="text-xs text-slate-300 mt-0.5">Belum dilewati</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

    </div>
@endsection