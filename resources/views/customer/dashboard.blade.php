@extends('layouts.app')

@section('title', 'Dashboard Customer')

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
    <a href="{{ route('customer.dashboard') }}"
        class="bnav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home text-xl"></i><span>Beranda</span>
    </a>
    <a href="{{ route('customer.order') }}" class="bnav-item {{ request()->routeIs('customer.order') ? 'active' : '' }}">
        <i class="fas fa-plus-circle text-xl"></i><span>Pesan</span>
    </a>
    <a href="{{ route('customer.tracking') }}"
        class="bnav-item {{ request()->routeIs('customer.tracking') ? 'active' : '' }}">
        <i class="fas fa-map-marker-alt text-xl"></i><span>Tracking</span>
    </a>
    <a href="{{ route('customer.profile') }}"
        class="bnav-item {{ request()->routeIs('customer.profile*') ? 'active' : '' }}">
        <i class="fas fa-user text-xl"></i><span>Profil</span>
    </a>
@endsection

@push('styles')
    <style>
        /* ─── Hero ─────────────────────────────────────────────────── */
        .hero-wrap {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #14532d 100%);
            border-radius: 24px;
            padding: 24px 20px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        /* decorative circles */
        .hero-wrap::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .hero-wrap::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        /* ─── Quick action buttons ──────────────────────────────────── */
        .qaction {
            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 16px;
            padding: 14px 8px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .2s, transform .15s;
            -webkit-tap-highlight-color: transparent;
        }

        .qaction:hover {
            background: rgba(255, 255, 255, .22);
        } 

        .qaction:active {
            transform: scale(.96);
        }

        /* ─── Stat card ─────────────────────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 16px 12px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .stat-num {
            font-weight: 900;
            line-height: 1;
        }

        .stat-lbl {
            font-size: .62rem;
            font-weight: 700;
            color: #94a3b8;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* ─── Section header ────────────────────────────────────────── */
        .sec-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .sec-head h2 {
            font-size: .95rem;
            font-weight: 900;
            color: #1e293b;
        }

        .sec-head a {
            font-size: .78rem;
            font-weight: 700;
            color: #16a34a;
            text-decoration: none;
        }

        /* ─── Order card ────────────────────────────────────────────── */
        .order-card {
            background: #fff;
            border-radius: 18px;
            padding: 14px 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
            display: block;
            text-decoration: none;
            transition: box-shadow .2s, transform .15s;
            -webkit-tap-highlight-color: transparent;
        }

        .order-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }

        .order-card:active {
            transform: scale(.99);
        }

        /* ─── Profile shortcut ──────────────────────────────────────── */
        .profile-shortcut {
            background: #fff;
            border-radius: 18px;
            padding: 14px 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: box-shadow .2s;
        }

        .profile-shortcut:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .07);
        }

        .p-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* ─── Pulse dot ─────────────────────────────────────────────── */
        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f59e0b;
            display: inline-block;
            animation: pulse 1.5s infinite;
            margin-right: 6px;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.85);
            }
        }

        /* ─── Responsive tweaks (hero on larger screens) ────────────── */
        @media (min-width: 640px) {
            .hero-wrap {
                padding: 32px 28px;
            }

            .hero-wrap::before {
                width: 260px;
                height: 260px;
                top: -80px;
                right: -80px;
            }

            .hero-wrap::after {
                width: 200px;
                height: 200px;
                bottom: -60px;
                left: -60px;
            }

            .stat-card {
                padding: 20px 16px;
            }
        }

        @media (min-width: 768px) {
            .hero-wrap {
                border-radius: 28px;
            }

            .qaction {
                padding: 18px 12px;
                border-radius: 18px;
            }

            .order-card {
                padding: 18px 20px;
            }
        }

        @media (min-width: 1024px) {
            .hero-wrap {
                padding: 36px 36px;
            }

            .stat-num {
                font-size: 1.6rem !important;
            }
        }
    </style>
@endpush

@section('content')
    {{-- ── Outer container: fluid on mobile, constrained & centered on desktop ── --}}
    <div class="w-full max-w-5xl mx-auto px-0 sm:px-2 md:px-4 space-y-4 sm:space-y-5 pb-8">

        {{-- ── HERO ─────────────────────────────────────────────────────────── --}}
        <div class="hero-wrap">
            <div style="position:relative;z-index:1">

                {{-- Greeting + headline --}}
                <p class="text-green-200 text-sm sm:text-base font-semibold mb-1">
                    Halo, {{ explode(' ', $user->name)[0] }} 👋
                </p>
                <h1
                    class="font-black leading-tight mb-5 sm:mb-7
                        text-xl sm:text-2xl md:text-3xl lg:text-4xl">
                    Mau kirim apa hari ini?
                </h1>

                {{-- Quick actions: 3 cols always, but padding/icon scales with breakpoint --}}
                <div class="grid grid-cols-2 gap-2.5 sm:gap-3 md:gap-4">
                    <a href="{{ route('customer.order') }}?type=kirim_barang" class="qaction w-full">
                        <span class="text-2xl sm:text-3xl md:text-4xl leading-none">📦</span>
                        <span class="text-xs sm:text-sm font-bold">Kirim Barang</span>
                    </a>
                    <a href="{{ route('customer.order') }}?type=antar_orang" class="qaction w-full">
                        <span class="text-2xl sm:text-3xl md:text-4xl leading-none">🛵</span>
                        <span class="text-xs sm:text-sm font-bold">Antar Orang</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── STATS ────────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-2.5 sm:gap-3 md:gap-4">
            {{-- Total Orders --}}
            <div class="stat-card">
                <div class="stat-num text-xl sm:text-2xl md:text-3xl" style="color:#16a34a">
                    {{ $totalOrders }}
                </div>
                <div class="stat-lbl">Total Order</div>
            </div>

            {{-- Active Orders --}}
            <div class="stat-card">
                <div class="stat-num text-xl sm:text-2xl md:text-3xl" style="color:#f59e0b">
                    {{ $activeOrders->count() }}
                </div>
                <div class="stat-lbl">Aktif</div>
            </div>

            {{-- Total Spend --}}
            <div class="stat-card">
                <div class="stat-num text-base sm:text-xl md:text-2xl" style="color:#3b82f6">
                    Rp {{ number_format($totalSpend / 1000, 0, ',', '.') }}K
                </div>
                <div class="stat-lbl">Total Bayar</div>
            </div>
        </div>

        {{-- ── ACTIVE ORDERS ────────────────────────────────────────────────── --}}
        @if ($activeOrders->isNotEmpty())
            <div>
                <div class="sec-head">
                    <h2><span class="pulse-dot"></span>Pesanan Aktif</h2>
                    <a href="{{ route('customer.tracking') }}">Lihat Semua →</a>
                </div>

                {{-- 1 col on mobile, 2 on sm+, 3 on lg+ --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($activeOrders as $order)
                        <a href="{{ route('customer.tracking') }}?order_id={{ $order->id }}" class="order-card">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="min-w-0">
                                    <p class="text-xs text-slate-400 font-bold">{{ $order->order_code }}</p>
                                    <p class="font-black text-slate-800 text-sm sm:text-base truncate">
                                        {{ $order->service_label }}
                                    </p>
                                </div>
                                <span class="badge badge-{{ $order->status }} flex-shrink-0 text-xs">
                                    {{ $order->status_badge['label'] }}
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-500 mb-2 truncate">
                                📍 {{ $order->pickup_address }} → {{ $order->destination_address }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400">
                                    {{ $order->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}
                                </span>
                                <span class="text-xs sm:text-sm font-black" style="color:#16a34a">
                                    Rp {{ number_format($order->total_fare, 0, ',', '.') }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── ORDER HISTORY ─────────────────────────────────────────────────── --}}
        <div>
            <div class="sec-head">
                <h2>Riwayat Pesanan</h2>
                <a href="{{ route('customer.profile') }}">Lihat Semua →</a>
            </div>

            {{-- 1 col on mobile, 2 col on md+ for a magazine-style grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($recentOrders as $order)
                    <a href="{{ route('customer.order.show', $order->id) }}" class="order-card">
                        <div class="flex items-center gap-3">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center
                            text-lg sm:text-xl rounded-2xl"
                                style="background:{{ $order->service_type === 'kirim_barang' ? '#dcfce7' : ($order->service_type === 'antar_orang' ? '#fef3c7' : '#dbeafe') }}">
                                {{ $order->service_type === 'kirim_barang' ? '📦' : ($order->service_type === 'antar_orang' ? '🛵' : '🍜') }}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 text-sm sm:text-base truncate">
                                    {{ $order->service_label }}
                                </p>
                                <p class="text-xs sm:text-sm text-slate-400 truncate">
                                    {{ $order->pickup_address }} → {{ $order->destination_address }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            {{-- Price + badge --}}
                            <div class="text-right flex-shrink-0">
                                <p class="font-black text-sm sm:text-base" style="color:#16a34a">
                                    Rp {{ number_format($order->total_fare, 0, ',', '.') }}
                                </p>
                                <span class="badge badge-{{ $order->status }} text-xs">
                                    {{ $order->status_badge['label'] }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="order-card md:col-span-2">
                        <div class="text-center py-10 px-4">
                            <div class="text-5xl mb-3">📭</div>
                            <p class="font-bold text-slate-600 text-sm sm:text-base">Belum ada pesanan</p>
                            <p class="text-xs sm:text-sm text-slate-400 mt-1">Yuk buat pesanan pertama Anda!</p>
                            <a href="{{ route('customer.order') }}"
                                class="inline-block mt-4 px-6 py-2.5 bg-green-600 hover:bg-green-700
                          text-white font-bold rounded-xl text-sm transition-colors">
                                Buat Pesanan
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── PROFILE SHORTCUT ──────────────────────────────────────────────── --}}
        <a href="{{ route('customer.profile') }}" class="profile-shortcut">
            {{-- Avatar --}}
            <div class="p-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>

            {{-- Name & email --}}
            <div class="flex-1 min-w-0">
                <p class="font-black text-slate-800 text-sm sm:text-base truncate">{{ $user->name }}</p>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5 truncate">{{ $user->email }}</p>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <span class="text-xs sm:text-sm font-bold hidden sm:inline" style="color:#16a34a">
                    Lihat Profil
                </span>
                <i class="fas fa-chevron-right text-xs text-slate-300"></i>
            </div>
        </a>

    </div>
@endsection
