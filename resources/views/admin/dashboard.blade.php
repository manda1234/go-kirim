@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('sidebar-nav')
  <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
  <a href="{{ route('admin.transactions') }}" class="sidebar-link"><i class="fas fa-receipt w-5"></i> Transaksi</a>
  <a href="{{ route('admin.customers') }}" class="sidebar-link"><i class="fas fa-users w-5"></i> Customer</a>
  <a href="{{ route('admin.mitras') }}" class="sidebar-link"><i class="fas fa-motorcycle w-5"></i> Mitra</a>
  <a href="{{ route('admin.bonus.index') }}" class="sidebar-link"><i class="fas fa-trophy w-5"></i> Bonus Performa</a>
  <a href="{{ route('admin.rates') }}" class="sidebar-link"><i class="fas fa-cog w-5"></i> Setting Tarif</a>
  <a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="fas fa-qrcode w-5"></i> Setting QRIS</a>
@endsection

@section('bottom-nav')
    <a href="{{ route('admin.dashboard') }}" class="bnav-item active">
        <i class="fas fa-home text-xl"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('admin.transactions') }}" class="bnav-item">
        <i class="fas fa-receipt text-xl"></i><span>Transaksi</span>
    </a>
    <a href="{{ route('admin.customers') }}" class="bnav-item">
        <i class="fas fa-users text-xl"></i><span>User</span>
    </a>
    <a href="{{ route('admin.rates') }}" class="bnav-item">
        <i class="fas fa-cog text-xl"></i><span>Setting</span>
    </a>
@endsection

@push('styles')
    <style>
        /* ── Stat Cards ── */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 18px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.06;
        }

        .stat-card.green::after {
            background: #16a34a;
        }

        .stat-card.blue::after {
            background: #2563eb;
        }

        .stat-card.purple::after {
            background: #7c3aed;
        }

        .stat-card.amber::after {
            background: #d97706;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
            flex-shrink: 0;
        }

        /* ── Status Pill ── */
        .status-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px 8px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            transition: all 0.2s;
            cursor: default;
            min-width: 0;
        }

        .status-pill:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
        }

        .status-count {
            font-size: 22px;
            font-weight: 900;
            line-height: 1;
        }

        /* ── Table ── */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tr:hover td {
            background: #fafbfc;
        }

        /* ── Quick link ── */
        .quick-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 18px;
            background: white;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s;
            text-decoration: none;
        }

        .quick-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #e2e8f0;
        }

        .quick-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        /* ── Mobile table scroll hint ── */
        .scroll-hint {
            display: none;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
            padding: 6px;
        }

        @media (max-width: 640px) {
            .scroll-hint {
                display: block;
            }

            .admin-table th:nth-child(3),
            .admin-table td:nth-child(3),
            .admin-table th:nth-child(4),
            .admin-table td:nth-child(4) {
                display: none;
            }
        }

        /* ── Header gradient ── */
        .admin-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
            position: relative;
            overflow: hidden;
        }

        .admin-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* ── Pending badge pulse ── */
        .pulse-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f59e0b;
            animation: pulse 1.5s infinite;
        }

        /* ── Revenue highlight ── */
        .revenue-bar {
            height: 6px;
            border-radius: 3px;
            background: #f1f5f9;
            overflow: hidden;
            margin-top: 8px;
        }

        .revenue-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #16a34a, #4ade80);
            transition: width 1s ease;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- ── HEADER ── --}}
        <div class="admin-header p-5 lg:rounded-b-3xl text-white relative">
            <div class="relative flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center text-sm">⚡</div>
                        <span class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Admin Panel</span>
                    </div>
                    <h1 class="text-2xl font-black tracking-tight">KirimCepat</h1>
                    <p class="text-slate-400 text-xs mt-1">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    <div class="flex items-center gap-2 bg-white/10 rounded-full px-3 py-1.5">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse flex-shrink-0"></span>
                        <span class="text-xs font-bold text-green-400">Live</span>
                    </div>
                    @if ($pendingOrders > 0)
                        <a href="{{ route('admin.transactions') }}?status=waiting" class="pulse-badge">
                            <span class="pulse-dot"></span>
                            {{ $pendingOrders }} menunggu
                        </a>
                    @endif
                </div>
            </div>

            {{-- Revenue summary strip --}}
            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="bg-white/10 rounded-2xl p-3">
                    <p class="text-xs text-slate-400 font-semibold">Revenue Hari Ini</p>
                    <p class="text-xl font-black text-white mt-0.5">
                        Rp {{ number_format($todayRevenue / 1000, 0, ',', '.') }}K
                    </p>
                    @php
                        $maxRevenue = max($revenueChart->max() ?? 1, 1);
                        $todayPct = min(100, ($todayRevenue / $maxRevenue) * 100);
                    @endphp
                    <div class="revenue-bar mt-2">
                        <div class="revenue-fill" style="width:{{ $todayPct }}%"></div>
                    </div>
                </div>
                <div class="bg-white/10 rounded-2xl p-3">
                    <p class="text-xs text-slate-400 font-semibold">Order Hari Ini</p>
                    <p class="text-xl font-black text-white mt-0.5">{{ $todayOrders }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ $orderStats['completed'] ?? 0 }} selesai · {{ $orderStats['cancelled'] ?? 0 }} batal
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4 space-y-4">

            {{-- ── STATS GRID ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Order --}}
                <div class="stat-card green">
                    <div class="stat-icon" style="background:#dcfce7">📦</div>
                    <p class="text-2xl font-black" style="color:#16a34a">{{ $todayOrders }}</p>
                    <p class="text-sm font-bold text-slate-700 mt-0.5">Order Hari Ini</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $pendingOrders }} menunggu mitra</p>
                </div>

                {{-- Revenue --}}
                <div class="stat-card blue">
                    <div class="stat-icon" style="background:#dbeafe">💰</div>
                    <p class="text-xl font-black" style="color:#2563eb">
                        Rp {{ number_format($todayRevenue / 1000, 0, ',', '.') }}K
                    </p>
                    <p class="text-sm font-bold text-slate-700 mt-0.5">Revenue</p>
                    <p class="text-xs text-slate-400 mt-1">dari pesanan selesai</p>
                </div>

                {{-- Customer --}}
                <div class="stat-card purple">
                    <div class="stat-icon" style="background:#ede9fe">👥</div>
                    <p class="text-2xl font-black" style="color:#7c3aed">{{ $totalCustomers }}</p>
                    <p class="text-sm font-bold text-slate-700 mt-0.5">Customer</p>
                    <p class="text-xs text-slate-400 mt-1">pengguna terdaftar</p>
                </div>

                {{-- Mitra --}}
                <div class="stat-card amber">
                    <div class="stat-icon" style="background:#fef3c7">🏍️</div>
                    <p class="text-2xl font-black" style="color:#d97706">{{ $activeMitras }}</p>
                    <p class="text-sm font-bold text-slate-700 mt-0.5">Mitra Online</p>
                    <p class="text-xs text-slate-400 mt-1">dari {{ $totalMitras }} total</p>
                </div>
            </div>

            {{-- ── STATUS SUMMARY ── --}}
            <div class="card p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-black text-slate-800 text-sm">Ringkasan Status Pesanan</h2>
                    <a href="{{ route('admin.transactions') }}" class="text-xs font-bold" style="color:#16a34a">Filter
                        →</a>
                </div>
                <div class="grid grid-cols-3 lg:grid-cols-6 gap-2">
                    @foreach ([['waiting', 'Menunggu', '#f59e0b', '#fffbeb', $orderStats['waiting'] ?? 0], ['accepted', 'Diterima', '#3b82f6', '#eff6ff', $orderStats['accepted'] ?? 0], ['picking_up', 'Menjemput', '#8b5cf6', '#f5f3ff', $orderStats['picking_up'] ?? 0], ['in_progress', 'Perjalanan', '#f97316', '#fff7ed', $orderStats['in_progress'] ?? 0], ['completed', 'Selesai', '#16a34a', '#f0fdf4', $orderStats['completed'] ?? 0], ['cancelled', 'Dibatalkan', '#ef4444', '#fef2f2', $orderStats['cancelled'] ?? 0]] as [$status, $label, $color, $bg, $count])
                        <a href="{{ route('admin.transactions') }}?status={{ $status }}" class="status-pill"
                            style="background:{{ $bg }}">
                            <span class="status-count" style="color:{{ $color }}">{{ $count }}</span>
                            <span
                                style="font-size:10px;font-weight:700;color:{{ $color }};opacity:0.7;margin-top:3px;text-align:center;line-height:1.2">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ── TRANSAKSI TERBARU ── --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <h2 class="font-black text-slate-800 text-sm">Transaksi Terbaru</h2>
                    <a href="{{ route('admin.transactions') }}" class="text-xs font-bold" style="color:#16a34a">
                        Lihat Semua →
                    </a>
                </div>

                {{-- Mobile: card list --}}
                <div class="block sm:hidden divide-y divide-slate-50">
                    @foreach ($recentOrders->take(5) as $order)
                        <a href="{{ route('admin.orders.detail', $order->id) }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0"
                                style="background:#f0fdf4">
                                {{ $order->service_type === 'kirim_barang' ? '📦' : ($order->service_type === 'antar_orang' ? '🛵' : '🍜') }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-slate-800">{{ $order->order_code }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $order->customer?->name ?? '–' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-black text-sm" style="color:#16a34a">Rp
                                    {{ number_format($order->total_fare / 1000, 0, ',', '.') }}K</p>
                                <span class="badge badge-{{ $order->status }}"
                                    style="font-size:9px">{{ $order->status_badge['label'] ?? $order->status }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Desktop: full table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <p class="scroll-hint"><i class="fas fa-arrows-alt-h mr-1"></i>Geser untuk lihat semua</p>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th>Mitra</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.detail', $order->id) }}"
                                            class="font-bold hover:underline" style="color:#16a34a">
                                            {{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black flex-shrink-0"
                                                style="background:#dbeafe;color:#1d4ed8">
                                                {{ strtoupper(substr($order->customer?->name ?? 'C', 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-sm text-slate-700 truncate max-w-[100px]">
                                                {{ $order->customer?->name ?? '–' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-slate-500 text-sm">
                                        @php
                                            $svcIcon = match ($order->service_type) {
                                                'kirim_barang' => '📦',
                                                'antar_orang' => '🛵',
                                                'food_delivery' => '🍜',
                                                default => '📋',
                                            };
                                        @endphp
                                        {{ $svcIcon }} {{ $order->service_label ?? $order->service_type }}
                                    </td>
                                    <td class="text-slate-500 text-sm">
                                        @if ($order->mitra?->name)
                                            {{ $order->mitra->name }}
                                        @else
                                            <span class="text-slate-300">–</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="font-black text-sm" style="color:#16a34a">
                                            Rp {{ number_format($order->total_fare, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->status }}">
                                            {{ $order->status_badge['label'] ?? $order->status }}
                                        </span>
                                    </td>
                                    <td class="text-slate-400 text-xs whitespace-nowrap">
                                        {{ $order->created_at->format('d M, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── QUICK LINKS ── --}}
            <div>
                <h2 class="font-black text-slate-700 text-sm mb-3">Kelola</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.customers') }}" class="quick-link">
                        <div class="quick-icon" style="background:#ede9fe">👥</div>
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-sm">Customer</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $totalCustomers }} terdaftar</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.mitras') }}" class="quick-link">
                        <div class="quick-icon" style="background:#fef3c7">🏍️</div>
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-sm">Mitra</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $activeMitras }} online</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.rates') }}" class="quick-link">
                        <div class="quick-icon" style="background:#dcfce7">💰</div>
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-sm">Tarif</p>
                            <p class="text-xs text-slate-400 mt-0.5">Motor & Mobil</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="quick-link">
                        <div class="quick-icon" style="background:#dbeafe">📋</div>
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-sm">Transaksi</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $todayOrders }} hari ini</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection