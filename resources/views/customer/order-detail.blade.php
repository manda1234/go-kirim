@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->order_code)

@section('sidebar-nav')
<a href="{{ route('customer.dashboard') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
          {{ request()->routeIs('customer.dashboard') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-primary-50 hover:text-primary-700' }}">
    <i class="fas fa-home w-5"></i> Beranda
</a>
<a href="{{ route('customer.order') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
          {{ request()->routeIs('customer.order') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-primary-50 hover:text-primary-700' }}">
    <i class="fas fa-plus-circle w-5"></i> Buat Pesanan
</a>
<a href="{{ route('customer.tracking') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
          {{ request()->routeIs('customer.tracking') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-primary-50 hover:text-primary-700' }}">
    <i class="fas fa-map-marker-alt w-5"></i> Lacak Pesanan
</a>
<a href="{{ route('customer.profile') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
          {{ request()->routeIs('customer.profile*') ? 'bg-primary-600 text-white' : 'text-slate-600 hover:bg-primary-50 hover:text-primary-700' }}">
    <i class="fas fa-user w-5"></i> Profil Saya
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('customer.dashboard') }}" class="bnav-item">
    <i class="fas fa-home text-xl"></i><span>Beranda</span>
</a>
<a href="{{ route('customer.order') }}" class="bnav-item">
    <i class="fas fa-plus-circle text-xl"></i><span>Pesan</span>
</a>
<a href="{{ route('customer.tracking') }}" class="bnav-item active">
    <i class="fas fa-map-marker-alt text-xl"></i><span>Tracking</span>
</a>
<a href="#" class="bnav-item">
    <i class="fas fa-user text-xl"></i><span>Profil</span>
</a>
@endsection

@push('styles')
<style>
    .timeline-item { position: relative; padding-left: 32px; padding-bottom: 20px; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 9px; top: 20px;
        width: 2px;
        bottom: 0;
        background: #e2e8f0;
    }
    .timeline-item:last-child::before { display: none; }
    .timeline-dot {
        position: absolute;
        left: 0; top: 4px;
        width: 20px; height: 20px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e2e8f0;
    }
    .timeline-dot.done  { background: #16a34a; box-shadow: 0 0 0 2px #bbf7d0; color: white; }
    .timeline-dot.active { background: #3b82f6; box-shadow: 0 0 0 2px #bfdbfe; color: white; animation: pulse 2s infinite; }
    .timeline-dot.pending { background: #f1f5f9; box-shadow: 0 0 0 2px #e2e8f0; color: #94a3b8; }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 2px #bfdbfe; }
        50%       { box-shadow: 0 0 0 6px rgba(59,130,246,0.2); }
    }

    .star-btn { font-size: 28px; cursor: pointer; color: #cbd5e1; transition: color 0.15s; }
    .star-btn.active, .star-btn:hover { color: #f59e0b; }

    .info-row {
        display: flex; align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 12px; font-weight: 700; color: #94a3b8; min-width: 110px; text-transform: uppercase; letter-spacing: 0.03em; }
    .info-value { font-size: 14px; font-weight: 600; color: #1e293b; flex: 1; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.tracking') }}"
           class="w-9 h-9 bg-white rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition flex-shrink-0">
            <i class="fas fa-arrow-left text-slate-600 text-sm"></i>
        </a>
        <div class="flex-1 min-w-0">
            <h1 class="font-black text-lg text-slate-800 truncate">
                Detail Pesanan
            </h1>
            <p class="text-xs text-slate-400 font-medium">#{{ $order->order_code }}</p>
        </div>
        <span class="badge badge-{{ $order->status }} flex-shrink-0">
            {{ $order->status_badge['label'] ?? ucfirst($order->status) }}
        </span>
    </div>

    {{-- STATUS BANNER --}}
    @php
        $bannerConfig = match($order->status) {
            'waiting'     => ['bg' => '#fef9c3', 'border' => '#fde047', 'color' => '#a16207', 'icon' => 'fa-clock',         'text' => 'Sedang mencari mitra terdekat...'],
            'accepted'    => ['bg' => '#dbeafe', 'border' => '#93c5fd', 'color' => '#1d4ed8', 'icon' => 'fa-check-circle',  'text' => 'Mitra sedang menuju lokasi penjemputan'],
            'picking_up'  => ['bg' => '#f3e8ff', 'border' => '#c4b5fd', 'color' => '#7e22ce', 'icon' => 'fa-motorcycle',    'text' => 'Mitra sedang dalam perjalanan ke pickup'],
            'in_progress' => ['bg' => '#ffedd5', 'border' => '#fdba74', 'color' => '#c2410c', 'icon' => 'fa-shipping-fast', 'text' => 'Pesanan sedang diantar'],
            'delivered'   => ['bg' => '#d1fae5', 'border' => '#6ee7b7', 'color' => '#065f46', 'icon' => 'fa-map-marker-alt','text' => 'Pesanan telah sampai di tujuan'],
            'on_the_way'  => ['bg' => '#f3e8ff', 'border' => '#c4b5fd', 'color' => '#7e22ce', 'icon' => 'fa-motorcycle',    'text' => 'Driver sedang dalam perjalanan ke Anda'],
            'picked_up'   => ['bg' => '#ffedd5', 'border' => '#fdba74', 'color' => '#c2410c', 'icon' => 'fa-user',          'text' => 'Perjalanan sedang berlangsung'],
            'arrived'     => ['bg' => '#d1fae5', 'border' => '#6ee7b7', 'color' => '#065f46', 'icon' => 'fa-map-marker-alt','text' => 'Anda telah sampai di tujuan'],
            'completed'   => ['bg' => '#dcfce7', 'border' => '#86efac', 'color' => '#15803d', 'icon' => 'fa-flag-checkered','text' => 'Pesanan selesai'],
            'cancelled'   => ['bg' => '#fee2e2', 'border' => '#fca5a5', 'color' => '#b91c1c', 'icon' => 'fa-times-circle',  'text' => 'Pesanan dibatalkan'],
            default       => ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'color' => '#64748b', 'icon' => 'fa-info-circle',   'text' => ucfirst($order->status)],
        };
    @endphp
    <div class="rounded-2xl p-4 flex items-center gap-3 border-2"
         style="background:{{ $bannerConfig['bg'] }};border-color:{{ $bannerConfig['border'] }};color:{{ $bannerConfig['color'] }}">
        <i class="fas {{ $bannerConfig['icon'] }} text-xl flex-shrink-0"></i>
        <div>
            <p class="font-bold text-sm">{{ $bannerConfig['text'] }}</p>
            @if($order->created_at)
            <p class="text-xs opacity-70 mt-0.5">
                Dibuat {{ $order->created_at->diffForHumans() }}
            </p>
            @endif
        </div>
    </div>

    {{-- MITRA INFO (jika sudah ada mitra) --}}
    @if($order->mitra)
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-motorcycle text-sm" style="color:#16a34a"></i>
            Info Mitra
        </h2>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl flex-shrink-0"
                 style="background:#dcfce7;color:#16a34a">
                {{ strtoupper(substr($order->mitra->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-black text-slate-800">{{ $order->mitra->name }}</p>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ $order->mitra->mitraProfile?->vehicle_brand ?? '–' }}
                    @if($order->mitra->mitraProfile?->vehicle_plate)
                    · <span class="font-bold text-slate-700">{{ $order->mitra->mitraProfile->vehicle_plate }}</span>
                    @endif
                </p>
                <div class="flex items-center gap-1 mt-1">
                    <i class="fas fa-star text-amber-400 text-xs"></i>
                    <span class="text-sm font-bold text-slate-700">{{ $order->mitra->mitraProfile?->rating ?? '5.0' }}</span>
                    <span class="text-xs text-slate-400">· {{ $order->mitra->mitraProfile?->total_trips ?? 0 }} trip</span>
                </div>
            </div>
            @if($order->mitra->phone)
            <a href="tel:{{ $order->mitra->phone }}"
               class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 text-white transition-all active:scale-95"
               style="background:#16a34a">
                <i class="fas fa-phone text-sm"></i>
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- RUTE PESANAN --}}
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-route text-sm" style="color:#16a34a"></i>
            Rute Pengiriman
        </h2>
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background:#dcfce7">
                    <span class="w-3 h-3 rounded-full" style="background:#16a34a"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Titik Jemput</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $order->pickup_address }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 px-1">
                <div class="w-6 flex justify-center">
                    <div class="w-0.5 h-8 border-l-2 border-dashed border-slate-200"></div>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                    <i class="fas fa-ruler-horizontal"></i>
                    {{ $order->distance_km ?? '–' }} km
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background:#fee2e2">
                    <span class="w-3 h-3 rounded-full" style="background:#ef4444"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Titik Tujuan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $order->destination_address }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL PESANAN --}}
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-sm" style="color:#16a34a"></i>
            Detail Pesanan
        </h2>
        <div>
            <div class="info-row">
                <span class="info-label">Kode Order</span>
                <span class="info-value font-black" style="color:#16a34a">#{{ $order->order_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Layanan</span>
                <span class="info-value">
                    @php
                        $serviceIcon = match($order->service_type) {
                            'kirim_barang' => '📦',
                            'antar_orang'  => '🛵',
                            'food_delivery'=> '🍜',
                            default        => '📋',
                        };
                        $serviceLabel = match($order->service_type) {
                            'kirim_barang' => 'Kirim Barang',
                            'antar_orang'  => 'Antar Orang',
                            'food_delivery'=> 'Food Delivery',
                            default        => $order->service_type,
                        };
                    @endphp
                    {{ $serviceIcon }} {{ $serviceLabel }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Kendaraan</span>
                <span class="info-value">
                    {{ $order->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}
                </span>
            </div>
            @if($order->item_type)
            <div class="info-row">
                <span class="info-label">Jenis Barang</span>
                <span class="info-value capitalize">{{ $order->item_type }}</span>
            </div>
            @endif
            @if($order->item_weight)
            <div class="info-row">
                <span class="info-label">Berat</span>
                <span class="info-value">{{ $order->item_weight }} kg</span>
            </div>
            @endif
            @if($order->notes)
            <div class="info-row">
                <span class="info-label">Catatan</span>
                <span class="info-value text-slate-600 italic">{{ $order->notes }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Pembayaran</span>
                <span class="info-value capitalize">
                    @php
                        $payIcon = match($order->payment_method) {
                            'cash'     => '💵',
                            'transfer' => '🏦',
                            'e_wallet' => '📱',
                            default    => '💳',
                        };
                    @endphp
                    {{ $payIcon }} {{ str_replace('_', ' ', ucfirst($order->payment_method)) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- FOOD ITEMS (jika ada) --}}
    @if($order->items && $order->items->isNotEmpty())
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
            🍜 Item Makanan
        </h2>
        <div class="space-y-2">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <div>
                    <p class="font-semibold text-sm text-slate-800">{{ $item->item_name }}</p>
                    <p class="text-xs text-slate-400">{{ $item->quantity }}x · Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                </div>
                <p class="font-bold text-sm text-slate-800">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- RINCIAN HARGA --}}
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-receipt text-sm" style="color:#16a34a"></i>
            Rincian Harga
        </h2>
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Ongkos Kirim</span>
                <span class="font-semibold text-slate-800">
                    Rp {{ number_format($order->base_fare ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500 font-medium">Biaya Layanan</span>
                <span class="font-semibold text-slate-800">
                    Rp {{ number_format($order->service_fee ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="border-t border-slate-100 pt-3 flex justify-between">
                <span class="font-black text-slate-800">Total</span>
                <span class="font-black text-lg" style="color:#16a34a">
                    Rp {{ number_format($order->total_fare ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- TIMELINE STATUS --}}
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-5 flex items-center gap-2">
            <i class="fas fa-history text-sm" style="color:#16a34a"></i>
            Riwayat Status
        </h2>
        @php
            if ($order->service_type === 'antar_orang') {
                $allStatuses = [
                    'waiting'    => ['label' => 'Mencari Driver',       'icon' => 'fa-clock'],
                    'accepted'   => ['label' => 'Driver Menerima',      'icon' => 'fa-check'],
                    'on_the_way' => ['label' => 'Menuju Lokasi',        'icon' => 'fa-motorcycle'],
                    'picked_up'  => ['label' => 'Dijemput',             'icon' => 'fa-user'],
                    'arrived'    => ['label' => 'Sampai Tujuan',        'icon' => 'fa-map-marker-alt'],
                    'completed'  => ['label' => 'Selesai',              'icon' => 'fa-flag-checkered'],
                ];
            } elseif ($order->service_type === 'food_delivery') {
                $allStatuses = [
                    'waiting'     => ['label' => 'Pesanan Dibuat',       'icon' => 'fa-clock'],
                    'accepted'    => ['label' => 'Kurir Menerima',       'icon' => 'fa-check'],
                    'picking_up'  => ['label' => 'Ambil Pesanan',        'icon' => 'fa-store'],
                    'in_progress' => ['label' => 'Sedang Diantar',       'icon' => 'fa-shipping-fast'],
                    'delivered'   => ['label' => 'Tiba di Tujuan',       'icon' => 'fa-map-marker-alt'],
                    'completed'   => ['label' => 'Selesai',              'icon' => 'fa-flag-checkered'],
                ];
            } else {
                $allStatuses = [
                    'waiting'     => ['label' => 'Pesanan Dibuat',       'icon' => 'fa-clock'],
                    'accepted'    => ['label' => 'Mitra Menerima',       'icon' => 'fa-check'],
                    'picking_up'  => ['label' => 'Menuju Pickup',        'icon' => 'fa-motorcycle'],
                    'in_progress' => ['label' => 'Sedang Diantar',       'icon' => 'fa-shipping-fast'],
                    'delivered'   => ['label' => 'Tiba di Tujuan',       'icon' => 'fa-map-marker-alt'],
                    'completed'   => ['label' => 'Selesai',              'icon' => 'fa-flag-checkered'],
                ];
            }
            $statusOrder  = array_keys($allStatuses);
            $currentIndex = array_search($order->status, $statusOrder);
            $trackingMap  = $order->trackings->keyBy('status');
        @endphp

        @if($order->status === 'cancelled')
        <div class="timeline-item">
            <div class="timeline-dot done" style="background:#ef4444;box-shadow:0 0 0 2px #fecaca">
                <i class="fas fa-times" style="font-size:8px"></i>
            </div>
            <p class="font-bold text-sm text-red-600">Pesanan Dibatalkan</p>
            @if($order->cancel_reason)
            <p class="text-xs text-slate-400 mt-0.5">{{ $order->cancel_reason }}</p>
            @endif
            @if($order->cancelled_at)
            <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, H:i') }}</p>
            @endif
        </div>
        @else
        @foreach($allStatuses as $statusKey => $statusInfo)
        @php
            $idx      = array_search($statusKey, $statusOrder);
            $isDone   = $idx <= $currentIndex && $currentIndex !== false;
            $isActive = $statusKey === $order->status;
            $tracking = $trackingMap[$statusKey] ?? null;
            $dotClass = $isDone ? ($isActive ? 'active' : 'done') : 'pending';
        @endphp
        <div class="timeline-item">
            <div class="timeline-dot {{ $dotClass }}">
                <i class="fas {{ $statusInfo['icon'] }}" style="font-size:8px"></i>
            </div>
            <div>
                <p class="font-bold text-sm {{ $isDone ? 'text-slate-800' : 'text-slate-400' }}">
                    {{ $statusInfo['label'] }}
                </p>
                @if($tracking)
                <p class="text-xs text-slate-500 mt-0.5">{{ $tracking->description }}</p>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ $tracking->created_at->format('d M Y, H:i') }}
                </p>
                @elseif(!$isDone)
                <p class="text-xs text-slate-300 mt-0.5">Menunggu...</p>
                @endif
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- RATING (jika sudah completed) --}}
    @if($order->status === 'completed')
    <div class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-star text-amber-400 text-sm"></i>
            @if($order->customer_rating) Penilaian Anda @else Beri Penilaian @endif
        </h2>

        @if($order->customer_rating)
        {{-- Sudah dinilai --}}
        <div class="text-center py-2">
            <div class="flex justify-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-2xl {{ $i <= $order->customer_rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                @endfor
            </div>
            <p class="font-bold text-slate-700">{{ $order->customer_rating }}/5</p>
            @if($order->customer_review)
            <p class="text-sm text-slate-500 mt-2 italic">"{{ $order->customer_review }}"</p>
            @endif
        </div>

        @else
        {{-- Belum dinilai --}}
        <form method="POST" action="{{ route('customer.order.rate', $order->id) }}" id="ratingForm">
            @csrf
            <div class="text-center mb-4">
                <p class="text-sm text-slate-500 mb-3">Bagaimana pengalaman Anda?</p>
                <div class="flex justify-center gap-2" id="starContainer">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            class="star-btn"
                            data-value="{{ $i }}"
                            onclick="setRating({{ $i }})">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="">
                <p class="text-xs text-slate-400 mt-2" id="ratingHint">Ketuk bintang untuk memberi nilai</p>
            </div>
            <div class="mb-4">
                <textarea name="review"
                          rows="3"
                          class="form-input resize-none"
                          placeholder="Ceritakan pengalaman Anda (opsional)..."></textarea>
            </div>
            <button type="submit"
                    id="submitRating"
                    disabled
                    class="w-full font-bold py-3 rounded-xl text-sm text-white transition-all"
                    style="background:#cbd5e1;cursor:not-allowed">
                <i class="fas fa-star mr-1"></i> Kirim Penilaian
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="flex gap-3 pb-4">
        <a href="{{ route('customer.tracking') }}"
           class="flex-1 py-3 rounded-xl font-bold text-sm text-center transition-all border-2 border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fas fa-list mr-1"></i> Semua Pesanan
        </a>
        @if(in_array($order->status, ['waiting', 'accepted']))
        {{-- Tombol batalkan (hanya jika masih bisa dibatalkan) --}}
        <form method="POST" action="#" class="flex-1">
            @csrf
            @method('POST')
            <button type="submit"
                    onclick="return confirm('Batalkan pesanan ini?')"
                    class="w-full py-3 rounded-xl font-bold text-sm transition-all border-2 text-red-600 hover:bg-red-50"
                    style="border-color:#fca5a5">
                <i class="fas fa-times mr-1"></i> Batalkan
            </button>
        </form>
        @elseif($order->status === 'completed')
        <a href="{{ route('customer.order') }}"
           class="flex-1 py-3 rounded-xl font-bold text-sm text-center text-white transition-all active:scale-95"
           style="background:#16a34a">
            <i class="fas fa-plus mr-1"></i> Pesan Lagi
        </a>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
const ratingLabels = ['', 'Sangat Buruk 😞', 'Kurang Baik 😕', 'Cukup 😐', 'Baik 😊', 'Sangat Baik 🤩'];
let selectedRating = 0;

function setRating(value) {
    selectedRating = value;
    document.getElementById('ratingInput').value = value;
    document.getElementById('ratingHint').textContent = ratingLabels[value];

    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, idx) => {
        star.classList.toggle('active', idx < value);
    });

    const submitBtn = document.getElementById('submitRating');
    submitBtn.disabled = false;
    submitBtn.style.background = '#16a34a';
    submitBtn.style.cursor = 'pointer';
}

// Hover effect
document.querySelectorAll('.star-btn').forEach((star, idx) => {
    star.addEventListener('mouseenter', () => {
        document.querySelectorAll('.star-btn').forEach((s, i) => {
            s.style.color = i <= idx ? '#f59e0b' : '#cbd5e1';
        });
    });
    star.addEventListener('mouseleave', () => {
        document.querySelectorAll('.star-btn').forEach((s, i) => {
            s.style.color = i < selectedRating ? '#f59e0b' : '#cbd5e1';
        });
    });
});
</script>
@endpush