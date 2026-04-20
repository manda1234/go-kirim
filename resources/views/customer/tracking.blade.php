@extends('layouts.app')
@section('title', 'Lacak Pesanan')

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
    <a href="{{ route('customer.profile') }}" class="bnav-item">
        <i class="fas fa-user text-xl"></i><span>Profil</span>
    </a>
@endsection

@push('styles')
    <style>
        /* ─── Root Layout ─────────────────────────────────────── */
        .page-wrap {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 12px 80px;
        }

        @media (min-width: 768px) {
            .page-wrap {
                padding: 0 16px 40px;
            }
        }

        @media (min-width: 1024px) {
            .page-wrap {
                padding: 0 20px 40px;
            }
        }

        /* ─── Main Grid ───────────────────────────────────────── */
        .track-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 1024px) {
            .track-grid {
                grid-template-columns: 1fr 268px;
                gap: 16px;
                align-items: start;
            }
        }

        /* ─── Card Base ───────────────────────────────────────── */
        .tk-card {
            background: white;
            border: 1px solid #e8ecf0;
            border-radius: 16px;
            overflow: hidden;
        }

        /* ─── Banner ──────────────────────────────────────────── */
        .order-banner {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            border-bottom: 1px solid transparent;
            flex-wrap: wrap;
        }

        .order-banner.green {
            background: #f0fdf4;
            border-color: #dcfce7;
        }

        .order-banner.blue {
            background: #eff6ff;
            border-color: #dbeafe;
        }

        .order-banner.red {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .order-banner.gray {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        /* ─── Route strip ─────────────────────────────────────── */
        .route-strip {
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 12px;
        }

        .route-dot-green {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #16a34a;
            flex-shrink: 0;
        }

        .route-dot-red {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ef4444;
            flex-shrink: 0;
        }

        .route-line {
            width: 1px;
            height: 16px;
            background: #cbd5e1;
            margin: 2px 0;
        }

        /* ─── Mitra row ───────────────────────────────────────── */
        .mitra-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
        }

        .mitra-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 13px;
            flex-shrink: 0;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            cursor: pointer;
            transition: background .15s;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: #f0fdf4;
        }

        /* ─── Tracking steps ──────────────────────────────────── */
        .steps-wrap {
            display: flex;
            flex-direction: column;
        }

        .step-row {
            display: flex;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .step-row:last-child {
            border-bottom: none;
        }

        .step-icon-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            width: 28px;
        }

        .step-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            transition: all .3s;
            flex-shrink: 0;
        }

        .step-icon.done {
            background: #16a34a;
            color: white;
            font-size: 13px;
        }

        .step-icon.active {
            background: #16a34a;
            color: white;
            font-size: 13px;
            animation: stepglow 2s infinite;
        }

        .step-icon.todo {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .step-line {
            width: 1px;
            flex: 1;
            min-height: 14px;
            margin-top: 3px;
            transition: background .5s;
        }

        .step-line.done {
            background: #86efac;
        }

        .step-line.todo {
            background: #e2e8f0;
        }

        .step-body {
            flex: 1;
            min-width: 0;
            padding-bottom: 6px;
        }

        .step-title {
            font-size: 12px;
            font-weight: 700;
            line-height: 1.3;
        }

        .step-title.done {
            color: #1e293b;
        }

        .step-title.todo {
            color: #94a3b8;
        }

        .step-desc {
            font-size: 10px;
            margin-top: 2px;
            line-height: 1.4;
        }

        .step-desc.done {
            color: #64748b;
        }

        .step-desc.todo {
            color: #cbd5e1;
        }

        .step-time {
            font-size: 10px;
            font-weight: 600;
            color: #16a34a;
            margin-top: 2px;
        }

        /* "Sekarang" pill */
        .now-pill {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 9px;
            font-weight: 800;
            color: #16a34a;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 1px 6px;
            border-radius: 20px;
            margin-left: 4px;
        }

        .now-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #16a34a;
            animation: blink 1.2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        @keyframes stepglow {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(22, 163, 74, .35);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(22, 163, 74, 0);
            }
        }

        /* ─── Cancelled box ───────────────────────────────────── */
        .cancel-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        /* ─── Total bar ───────────────────────────────────────── */
        .total-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-radius: 10px;
            padding: 9px 12px;
        }

        /* ─── Action buttons ──────────────────────────────────── */
        .btn-cancel {
            width: 100%;
            padding: 10px;
            border: 2px solid #fecaca;
            border-radius: 12px;
            color: #ef4444;
            font-size: 12px;
            font-weight: 700;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s, transform .1s;
        }

        .btn-cancel:hover {
            background: #fef2f2;
        }

        .btn-cancel:active {
            transform: scale(.98);
        }

        .btn-rate {
            width: 100%;
            padding: 10px;
            border: 2px solid #fde68a;
            border-radius: 12px;
            color: #92400e;
            font-size: 12px;
            font-weight: 700;
            background: #fef9c3;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background .15s, transform .1s;
        }

        .btn-rate:active {
            transform: scale(.98);
        }

        /* ─── History list ────────────────────────────────────── */
        .hist-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            background: white;
            text-decoration: none;
            transition: box-shadow .15s, transform .1s;
        }

        .hist-item:hover {
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        .hist-item:active {
            transform: scale(.99);
        }

        /* ─── Sidebar cards ───────────────────────────────────── */
        .side-card {
            background: white;
            border: 1px solid #e8ecf0;
            border-radius: 14px;
            padding: 12px;
        }

        .side-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .active-order-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px;
            border-radius: 10px;
            border: 1px solid transparent;
            text-decoration: none;
            transition: background .15s;
        }

        .active-order-row:hover {
            background: #f8fafc;
        }

        .active-order-row.selected {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        /* ─── Chat Box ────────────────────────────────────────── */
        #chatBox {
            display: none;
            flex-direction: column;
            position: fixed;
            bottom: 72px;
            right: 10px;
            width: min(310px, calc(100vw - 16px));
            max-height: 400px;
            z-index: 999;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .15);
            background: white;
            border: 1px solid #e2e8f0;
        }

        @media (min-width: 1024px) {
            #chatBox {
                right: 20px;
                bottom: 20px;
                width: 308px;
            }
        }

        #chatBox.open {
            display: flex;
        }

        #chatMessages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-height: 140px;
            max-height: 240px;
        }

        .cb {
            max-width: 80%;
            padding: 7px 10px;
            border-radius: 13px;
            font-size: 12px;
            line-height: 1.4;
            word-break: break-word;
        }

        .cb.mine {
            align-self: flex-end;
            background: #16a34a;
            color: white;
            border-bottom-right-radius: 3px;
        }

        .cb.theirs {
            align-self: flex-start;
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 3px;
        }

        .cb-time {
            font-size: 9px;
            opacity: .55;
            margin-top: 2px;
            text-align: right;
        }

        .cb.theirs .cb-time {
            text-align: left;
        }

        .cb-name {
            font-size: 9px;
            font-weight: 700;
            opacity: .6;
            margin-bottom: 1px;
        }

        /* chat badge */
        .chat-btn-wrap {
            position: relative;
            display: inline-block;
        }

        #chatUnreadBadge {
            display: none;
            position: absolute;
            top: -3px;
            right: -3px;
            background: #ef4444;
            color: white;
            font-size: 9px;
            font-weight: 800;
            min-width: 15px;
            height: 15px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 2px solid white;
        }

        #chatUnreadBadge.show {
            display: flex;
        }

        /* ─── Modals ──────────────────────────────────────────── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9000;
            background: rgba(0, 0, 0, .45);
            align-items: flex-end;
            justify-content: center;
            padding: 0;
        }

        @media (min-width: 640px) {
            .modal-backdrop {
                align-items: center;
                padding: 16px;
            }
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal-box {
            background: white;
            width: 100%;
            max-width: 380px;
            border-radius: 20px 20px 0 0;
            padding: 20px 16px;
            animation: slideup .25s ease;
        }

        @media (min-width: 640px) {
            .modal-box {
                border-radius: 20px;
                padding: 24px 20px;
            }
        }

        @keyframes slideup {
            from {
                transform: translateY(40px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        /* badge helper */
        .bdg {
            display: inline-flex;
            align-items: center;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .bdg-green {
            background: #dcfce7;
            color: #15803d;
        }

        .bdg-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .bdg-yellow {
            background: #fef9c3;
            color: #a16207;
        }

        .bdg-red {
            background: #fee2e2;
            color: #dc2626;
        }

        .bdg-gray {
            background: #f1f5f9;
            color: #475569;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrap">

        {{-- Definisikan chat status SEKALI di sini --}}
        @php
            $bankData = [
                'bni' => [
                    'name' => 'BNI',
                    'full' => 'Bank Negara Indonesia',
                    'color' => '#f97316',
                    'no_rek' => '1234567890',
                ],
                'bri' => [
                    'name' => 'BRI',
                    'full' => 'Bank Rakyat Indonesia',
                    'color' => '#2563eb',
                    'no_rek' => '1234567890',
                ],
                'mandiri' => [
                    'name' => 'Mandiri',
                    'full' => 'Bank Mandiri',
                    'color' => '#ca8a04',
                    'no_rek' => '1234567890',
                ],
                'bca' => [
                    'name' => 'BCA',
                    'full' => 'Bank Central Asia',
                    'color' => '#0ea5e9',
                    'no_rek' => '1234567890',
                ],
                'bsi' => [
                    'name' => 'BSI',
                    'full' => 'Bank Syariah Indonesia',
                    'color' => '#10b981',
                    'no_rek' => '1234567890',
                ],
                'seabank' => [
                    'name' => 'SeaBank',
                    'full' => 'Sea Bank Indonesia',
                    'color' => '#6366f1',
                    'no_rek' => '1234567890',
                ],
                'btn' => [
                    'name' => 'BTN',
                    'full' => 'Bank Tabungan Negara',
                    'color' => '#dc2626',
                    'no_rek' => '1234567890',
                ],
                'cimb' => ['name' => 'CIMB', 'full' => 'CIMB Niaga', 'color' => '#b91c1c', 'no_rek' => '1234567890'],
            ];
            $selectedBank = $bankData[$selected?->bank_selected ?? ''] ?? null;


               $ewalletData = [
        'gopay'     => ['name' => 'GoPay',     'color' => '#00AED6', 'bg' => '#e0f7ff'],
        'ovo'       => ['name' => 'OVO',        'color' => '#4c3494', 'bg' => '#ede9fe'],
        'dana'      => ['name' => 'DANA',       'color' => '#1185e0', 'bg' => '#dbeafe'],
        'shopeepay' => ['name' => 'ShopeePay',  'color' => '#ef4444', 'bg' => '#fee2e2'],
        'linkaja'   => ['name' => 'LinkAja',    'color' => '#e8282b', 'bg' => '#fee2e2'],
        'astrapay'  => ['name' => 'AstraPay',   'color' => '#0d4f9e', 'bg' => '#dbeafe'],
    ];
    $selectedEwallet = $ewalletData[$selected?->ewallet_selected ?? ''] ?? null;
        @endphp

        @php
            $chatAllowedStatuses = $selected?->isRide()
                ? ['accepted', 'on_the_way', 'picked_up', 'arrived']
                : ['accepted', 'picking_up', 'in_progress', 'delivered'];
            $chatEnabled = $selected && $selected->mitra && in_array($selected->status, $chatAllowedStatuses ?? []);
        @endphp

        {{-- ── Page Header ───────────────────────── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 2px 10px;">
            <h1 style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">
                Lacak Pesanan 📍
            </h1>
            @if ($activeOrders->isNotEmpty())
                <span class="bdg bdg-green" style="gap:4px;">
                    <span
                        style="width:6px;height:6px;border-radius:50%;background:#16a34a;animation:blink 1.2s infinite;display:inline-block;"></span>
                    {{ $activeOrders->count() }} aktif
                </span>
            @endif
        </div>

        {{-- ══ MAIN GRID ══ --}}
        <div class="track-grid">

            {{-- ╔══════════════════════════════════════════╗
                 ║  KOLOM KIRI — order card + history      ║
                 ╚══════════════════════════════════════════╝ --}}
            <div style="display:flex;flex-direction:column;gap:12px;min-width:0;">

                @if ($selected)
                    {{-- ── ORDER CARD ── --}}
                    <div class="tk-card">

                        {{-- Banner --}}
                        <div
                            class="order-banner {{ $selected->status === 'cancelled' ? 'red' : ($selected->isRide() ? 'blue' : 'green') }}">
                            <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                <span style="font-size:18px;flex-shrink:0;">
                                    @if ($selected->status === 'cancelled')
                                        ❌
                                    @elseif($selected->status === 'completed')
                                        🎉
                                    @elseif($selected->isRide())
                                        🛵
                                    @else
                                        📦
                                    @endif
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-weight:900;font-size:12px;color:#0f172a;margin:0;">
                                        {{ $selected->order_code }}
                                    </p>
                                    <p
                                        style="font-size:10px;color:#64748b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $selected->service_label }}
                                        · {{ $selected->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}
                                        @if ($selected->distance_km)
                                            · {{ $selected->distance_km }} km
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @php
                                $badgeCls = match ($selected->status) {
                                    'completed' => 'bdg-green',
                                    'cancelled' => 'bdg-red',
                                    'waiting' => 'bdg-yellow',
                                    default => 'bdg-blue',
                                };
                            @endphp
                            <span class="bdg {{ $badgeCls }}" style="flex-shrink:0;">
                                {{ $selected->status_badge['label'] }}
                            </span>
                        </div>

                        <div style="padding:12px;display:flex;flex-direction:column;gap:10px;">

                            {{-- Route --}}
                            <div class="route-strip">
                                <div style="display:flex;gap:8px;align-items:flex-start;">
                                    <div
                                        style="display:flex;flex-direction:column;align-items:center;padding-top:3px;flex-shrink:0;">
                                        <div class="route-dot-green"></div>
                                        <div class="route-line"></div>
                                        <div class="route-dot-red"></div>
                                    </div>
                                    <div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:8px;">
                                        <div>
                                            <p
                                                style="font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 1px;">
                                                {{ $selected->isRide() ? 'Jemput' : 'Pickup' }}
                                            </p>
                                            <p style="font-size:11px;color:#334155;margin:0;line-height:1.4;">
                                                {{ $selected->pickup_address }}
                                            </p>
                                        </div>
                                        <div>
                                            <p
                                                style="font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 1px;">
                                                Tujuan
                                            </p>
                                            <p style="font-size:11px;color:#334155;margin:0;line-height:1.4;">
                                                {{ $selected->destination_address }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Mitra / Driver --}}
                            @if ($selected->mitra)
                                <div class="mitra-row"
                                    style="background:{{ $selected->isRide() ? '#eff6ff' : '#f0fdf4' }};">
                                    <div class="mitra-avatar">
                                        {{ strtoupper(substr($selected->mitra->name, 0, 1)) }}
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <p
                                            style="font-weight:700;font-size:12px;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $selected->mitra->name }}
                                        </p>
                                        <p style="font-size:10px;color:#64748b;margin:0;">
                                            {{ $selected->mitra->mitraProfile?->vehicle_brand ?? 'Motor' }}
                                            &nbsp;·&nbsp;
                                            <span style="color:#f59e0b;font-weight:700;">
                                                ⭐ {{ $selected->mitra->mitraProfile?->rating ?? '5.0' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div style="display:flex;gap:6px;flex-shrink:0;">
                                        @if ($selected->mitra->phone)
                                            <a href="tel:{{ $selected->mitra->phone }}" class="icon-btn" title="Telepon">
                                                <i class="fas fa-phone" style="font-size:11px;"></i>
                                            </a>
                                        @endif
                                        @if ($chatEnabled)
                                            <div class="chat-btn-wrap">
                                                <button class="icon-btn" onclick="toggleChat()" title="Chat">
                                                    <i class="fas fa-comment" style="font-size:11px;"></i>
                                                </button>
                                                <span id="chatUnreadBadge"></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- ══ TRACKING STEPS ══ --}}
                            @if ($selected->service_type === 'kirim_barang')
                                @php
                                    $steps = [
                                        [
                                            'status' => 'waiting',
                                            'icon' => '📦',
                                            'title' => 'Pesanan Dibuat',
                                            'desc' => 'Mencari mitra terdekat',
                                        ],
                                        [
                                            'status' => 'accepted',
                                            'icon' => '✅',
                                            'title' => 'Mitra Menerima',
                                            'desc' => 'Mitra siap mengambil barang',
                                        ],
                                        [
                                            'status' => 'picking_up',
                                            'icon' => '🚚',
                                            'title' => 'Ambil Barang',
                                            'desc' => 'Mitra menuju lokasi pickup',
                                        ],
                                        [
                                            'status' => 'in_progress',
                                            'icon' => '🛵',
                                            'title' => 'Dalam Perjalanan',
                                            'desc' => 'Barang dikirim ke tujuan',
                                        ],
                                        [
                                            'status' => 'completed',
                                            'icon' => '🎉',
                                            'title' => 'Selesai',
                                            'desc' => 'Barang sampai di tujuan',
                                        ],
                                    ];
                                    $statusOrder = ['waiting', 'accepted', 'picking_up', 'in_progress', 'completed'];
                                @endphp
                            @elseif($selected->service_type === 'antar_orang')
                                @php
                                    $steps = [
                                        [
                                            'status' => 'waiting',
                                            'icon' => '🕐',
                                            'title' => 'Mencari Driver',
                                            'desc' => 'Sistem mencari driver terdekat',
                                        ],
                                        [
                                            'status' => 'accepted',
                                            'icon' => '✅',
                                            'title' => 'Driver Menerima',
                                            'desc' => 'Driver menerima pesanan',
                                        ],
                                        [
                                            'status' => 'on_the_way',
                                            'icon' => '🛵',
                                            'title' => 'Menuju Lokasi',
                                            'desc' => 'Driver dalam perjalanan ke Anda',
                                        ],
                                        [
                                            'status' => 'picked_up',
                                            'icon' => '👤',
                                            'title' => 'Dijemput',
                                            'desc' => 'Perjalanan berlangsung',
                                        ],
                                        [
                                            'status' => 'arrived',
                                            'icon' => '🏁',
                                            'title' => 'Sampai Tujuan',
                                            'desc' => 'Anda telah tiba di tujuan',
                                        ],
                                        [
                                            'status' => 'completed',
                                            'icon' => '🎉',
                                            'title' => 'Perjalanan Selesai',
                                            'desc' => 'Terima kasih telah menggunakan layanan kami',
                                        ],
                                    ];
                                    $statusOrder = [
                                        'waiting',
                                        'accepted',
                                        'on_the_way',
                                        'picked_up',
                                        'arrived',
                                        'completed',
                                    ];
                                @endphp
                            @else
                                @php
                                    $steps = [
                                        [
                                            'status' => 'waiting',
                                            'icon' => '🍜',
                                            'title' => 'Pesanan Masuk',
                                            'desc' => 'Mencari kurir makanan',
                                        ],
                                        [
                                            'status' => 'accepted',
                                            'icon' => '✅',
                                            'title' => 'Kurir Menerima',
                                            'desc' => 'Kurir siap mengambil pesanan',
                                        ],
                                        [
                                            'status' => 'picking_up',
                                            'icon' => '🏪',
                                            'title' => 'Ambil Pesanan',
                                            'desc' => 'Kurir di restoran',
                                        ],
                                        [
                                            'status' => 'in_progress',
                                            'icon' => '🛵',
                                            'title' => 'Sedang Diantar',
                                            'desc' => 'Pesanan menuju lokasi Anda',
                                        ],
                                        [
                                            'status' => 'completed',
                                            'icon' => '🎉',
                                            'title' => 'Pesanan Tiba',
                                            'desc' => 'Selamat menikmati!',
                                        ],
                                    ];
                                    $statusOrder = ['waiting', 'accepted', 'picking_up', 'in_progress', 'completed'];
                                @endphp
                            @endif

                            @php
                                $curIdx = array_search($selected->status, $statusOrder);
                                if ($curIdx === false) {
                                    $curIdx = -1;
                                }
                            @endphp

                            <div>
                                <p
                                    style="font-size:9px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin:0 0 6px;">
                                    Status Perjalanan
                                </p>
                                <div class="steps-wrap">
                                    @foreach ($steps as $i => $step)
                                        @php
                                            $sIdx = array_search($step['status'], $statusOrder);
                                            $isDone = $curIdx >= $sIdx;
                                            $isActive = $selected->status === $step['status'];
                                            $trk = $selected->trackings->firstWhere('status', $step['status']);
                                            $iconCls = $isActive ? 'active' : ($isDone ? 'done' : 'todo');
                                            $lineCls = $isDone ? 'done' : 'todo';
                                        @endphp
                                        <div class="step-row">
                                            <div class="step-icon-col">
                                                <div class="step-icon {{ $iconCls }}">
                                                    {{ $isDone ? $step['icon'] : $i + 1 }}
                                                </div>
                                                @if (!$loop->last)
                                                    <div class="step-line {{ $lineCls }}"></div>
                                                @endif
                                            </div>
                                            <div class="step-body">
                                                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:0;">
                                                    <span class="step-title {{ $isDone ? 'done' : 'todo' }}">
                                                        {{ $step['title'] }}
                                                    </span>
                                                    @if ($isActive)
                                                        <span class="now-pill">
                                                            <span class="now-dot"></span>Sekarang
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="step-desc {{ $isDone ? 'done' : 'todo' }}">
                                                    {{ $trk?->description ?? $step['desc'] }}
                                                </p>
                                                @if ($trk)
                                                    <p class="step-time">
                                                        <i class="fas fa-clock" style="font-size:8px;"></i>
                                                        {{ $trk->created_at->format('H:i · d M Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Cancelled note --}}
                                    @if ($selected->status === 'cancelled')
                                        <div class="cancel-box" style="margin-top:8px;">
                                            <span style="font-size:16px;flex-shrink:0;line-height:1;">❌</span>
                                            <div style="flex:1;min-width:0;">
                                                <p style="font-weight:800;font-size:11px;color:#dc2626;margin:0;">Pesanan
                                                    Dibatalkan</p>
                                                <p style="font-size:10px;color:#f87171;margin:2px 0 0;">
                                                    Oleh <strong
                                                        class="capitalize">{{ $selected->cancelled_by ?? '-' }}</strong>
                                                    @if ($selected->cancelled_at)
                                                        · {{ $selected->cancelled_at->format('d M Y, H:i') }}
                                                    @endif
                                                </p>
                                                @if ($selected->cancel_reason)
                                                    <p
                                                        style="font-size:10px;color:#475569;margin:4px 0 0;background:white;border:1px solid #fecaca;border-radius:8px;padding:4px 8px;">
                                                        <strong>Alasan:</strong> {{ $selected->cancel_reason }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- ══ END STEPS ══ --}}

                            {{-- Total --}}
                            <div class="total-bar">
                                <span style="font-size:11px;font-weight:700;color:#64748b;">Total Pembayaran</span>
                                <span style="font-size:14px;font-weight:900;color:#16a34a;">
                                    Rp {{ number_format($selected->total_fare, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- ══ PAYMENT SECTION ══ --}}

                            {{-- QRIS --}}
                            @if ($selected->payment_method === 'qris' && !$selected->isPaid())
                                <a href="{{ route('customer.payment', $selected) }}"
                                    style="display:flex;align-items:center;justify-content:center;gap:6px;
                                           width:100%;padding:10px;border-radius:12px;
                                           background:#16a34a;color:white;font-size:12px;font-weight:700;
                                           text-decoration:none;transition:opacity .15s;"
                                    onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                                    <i class="fas fa-qrcode" style="font-size:12px;"></i>
                                    {{ $selected->payment_proof ? 'Lihat Status Pembayaran' : 'Bayar Sekarang (QRIS)' }}
                                </a>
                            @endif

                            @if ($selected->payment_method === 'qris' && $selected->isPaid())
                                <div
                                    style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                                            border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;">
                                    <i class="fas fa-check-circle" style="color:#16a34a;font-size:13px;"></i>
                                    <div>
                                        <p style="font-size:11px;font-weight:800;color:#15803d;margin:0;">Pembayaran
                                            Dikonfirmasi</p>
                                        <p style="font-size:10px;color:#16a34a;margin:0;">
                                            {{ $selected->paid_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- TRANSFER --}}
                            @if ($selected->payment_method === 'transfer')

                                @if (!$selected->isPaid() && !$selected->payment_proof)
                                    {{-- Belum upload bukti transfer --}}
                                    <form method="POST" action="{{ route('customer.payment.upload', $selected->id) }}"
                                        enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:8px;">
                                        @csrf
                                        <div
                                            style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:10px 12px;">
                                            <p style="font-size:11px;font-weight:800;color:#92400e;margin:0 0 4px;">
                                                💳 Pembayaran Transfer Bank
                                            </p>
                                            <p style="font-size:10px;color:#a16207;margin:0;">
                                                Silakan transfer ke rekening berikut, lalu upload bukti transfer.
                                            </p>
                                            {{-- Info rekening dinamis --}}
                                            <div
                                                style="margin-top:8px;background:white;border-radius:8px;padding:8px 10px;border:1px solid #fde68a;">
                                                @if ($selectedBank)
                                                    <div
                                                        style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                                        <div
                                                            style="width:28px;height:28px;border-radius:6px;
                        background:{{ $selectedBank['color'] }};
                        display:flex;align-items:center;justify-content:center;">
                                                            <span
                                                                style="font-size:9px;font-weight:800;color:white;letter-spacing:-0.5px;">
                                                                {{ $selectedBank['name'] }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p
                                                                style="font-size:11px;font-weight:700;color:#1e293b;margin:0;">
                                                                {{ $selectedBank['full'] }}
                                                            </p>
                                                            <p style="font-size:10px;color:#64748b;margin:0;">
                                                                {{ $selectedBank['name'] }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <p
                                                        style="font-size:15px;font-weight:900;color:#0f172a;margin:2px 0;letter-spacing:1px;">
                                                        {{ $selectedBank['no_rek'] }}
                                                    </p>
                                                    <p style="font-size:10px;color:#64748b;margin:0;">a.n. KirimCepat</p>
                                                @else
                                                    <p style="font-size:10px;color:#94a3b8;margin:0;">
                                                        <i class="fas fa-exclamation-circle" style="color:#f59e0b;"></i>
                                                        Info rekening tidak ditemukan. Hubungi admin.
                                                    </p>
                                                @endif
                                            </div>
                                            <div
                                                style="margin-top:8px;display:flex;justify-content:space-between;
                                                        align-items:center;background:white;border-radius:8px;
                                                        padding:7px 10px;border:1px solid #fde68a;">
                                                <span style="font-size:10px;color:#64748b;">Total Transfer</span>
                                                <span style="font-size:13px;font-weight:900;color:#16a34a;">
                                                    Rp {{ number_format($selected->total_fare, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                        <label style="display:flex;flex-direction:column;gap:5px;">
                                            <span style="font-size:10px;font-weight:700;color:#475569;">
                                                Upload Bukti Transfer <span style="color:#ef4444;">*</span>
                                            </span>
                                            <input type="file" name="payment_proof" accept="image/*" required
                                                style="font-size:11px;border:1.5px solid #e2e8f0;border-radius:10px;
                                                       padding:7px 10px;background:white;width:100%;box-sizing:border-box;">
                                        </label>
                                        <button type="submit"
                                            style="width:100%;padding:10px;border-radius:12px;border:none;
                                                   background:#16a34a;color:white;font-size:12px;font-weight:700;
                                                   cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                                            <i class="fas fa-upload" style="font-size:11px;"></i>
                                            Upload Bukti Transfer
                                        </button>
                                    </form>
                                @elseif($selected->payment_proof && !$selected->isPaid())
                                    {{-- Sudah upload, menunggu konfirmasi admin --}}
                                    <div
                                        style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                                                border-radius:12px;background:#fffbeb;border:1px solid #fde68a;">
                                        <i class="fas fa-clock" style="color:#f59e0b;font-size:13px;"></i>
                                        <div>
                                            <p style="font-size:11px;font-weight:800;color:#92400e;margin:0;">
                                                Menunggu Konfirmasi Admin
                                            </p>
                                            <p style="font-size:10px;color:#a16207;margin:0;">
                                                Bukti transfer Anda sedang diverifikasi
                                            </p>
                                        </div>
                                    </div>

                                @endif

                                @endif

          {{-- E-WALLET --}}
@if ($selected->payment_method === 'e_wallet')

    @if (!$selected->isPaid() && !$selected->payment_proof)
        {{-- Belum upload bukti --}}
        <form method="POST"
            action="{{ route('customer.payment.upload', $selected->id) }}"
            enctype="multipart/form-data"
            style="display:flex;flex-direction:column;gap:8px;">
            @csrf
            <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:10px 12px;">
                <p style="font-size:11px;font-weight:800;color:#92400e;margin:0 0 4px;">
                    💳 Pembayaran via {{ $selectedEwallet['name'] ?? 'E-Wallet' }}
                </p>
                <p style="font-size:10px;color:#a16207;margin:0;">
                    Selesaikan pembayaran melalui aplikasi {{ $selectedEwallet['name'] ?? 'e-wallet' }} Anda,
                    lalu upload screenshot bukti pembayaran.
                </p>
                <div style="margin-top:8px;background:white;border-radius:8px;
                            padding:8px 10px;border:1px solid #fed7aa;
                            display:flex;align-items:center;gap:8px;">
                    <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;
                                background:{{ $selectedEwallet['bg'] ?? '#f1f5f9' }};
                                display:flex;align-items:center;justify-content:center;">
                        <span style="font-size:10px;font-weight:800;
                                     color:{{ $selectedEwallet['color'] ?? '#64748b' }};">
                            {{ $selectedEwallet['name'] ?? 'E-Wallet' }}
                        </span>
                    </div>
                    <div>
                        <p style="font-size:11px;font-weight:700;color:#1e293b;margin:0;">
                            {{ $selectedEwallet['name'] ?? 'E-Wallet' }}
                        </p>
                        <p style="font-size:10px;color:#64748b;margin:0;">
                            Transfer sesuai total tagihan
                        </p>
                    </div>
                    <div style="margin-left:auto;text-align:right;">
                        <p style="font-size:13px;font-weight:900;color:#16a34a;margin:0;">
                            Rp {{ number_format($selected->total_fare, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            <label style="display:flex;flex-direction:column;gap:5px;">
                <span style="font-size:10px;font-weight:700;color:#475569;">
                    Upload Screenshot Bukti <span style="color:#ef4444;">*</span>
                </span>
                <input type="file" name="payment_proof" accept="image/*" required
                    style="font-size:11px;border:1.5px solid #e2e8f0;border-radius:10px;
                           padding:7px 10px;background:white;width:100%;box-sizing:border-box;">
            </label>
            <button type="submit"
                style="width:100%;padding:10px;border-radius:12px;border:none;
                       background:#16a34a;color:white;font-size:12px;font-weight:700;
                       cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                <i class="fas fa-upload" style="font-size:11px;"></i>
                Upload Bukti Pembayaran
            </button>
        </form>

    @elseif($selected->payment_proof && !$selected->isPaid())
        {{-- Sudah upload, menunggu konfirmasi admin --}}
        <div style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                    border-radius:12px;background:#fff7ed;border:1px solid #fed7aa;">
            <i class="fas fa-clock" style="color:#f59e0b;font-size:13px;"></i>
            <div>
                <p style="font-size:11px;font-weight:800;color:#92400e;margin:0;">
                    Menunggu Konfirmasi Admin
                </p>
                <p style="font-size:10px;color:#a16207;margin:0;">
                    Bukti pembayaran {{ $selectedEwallet['name'] ?? 'e-wallet' }} sedang diverifikasi
                </p>
            </div>
        </div>
    @endif

    @if ($selected->isPaid())
        {{-- Sudah dikonfirmasi --}}
        <div style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                    border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;">
            <i class="fas fa-check-circle" style="color:#16a34a;font-size:13px;"></i>
            <div>
                <p style="font-size:11px;font-weight:800;color:#15803d;margin:0;">
                    Pembayaran Dikonfirmasi
                </p>
                <p style="font-size:10px;color:#16a34a;margin:0;">
                    {{ $selected->paid_at?->format('d M Y, H:i') }}
                </p>
            </div>
        </div>
    @endif



            {{-- ══ END PAYMENT SECTION ══ --}}

                                @if ($selected->isPaid())
                                    {{-- Sudah dikonfirmasi --}}
                                    <div
                                        style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                                                border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;">
                                        <i class="fas fa-check-circle" style="color:#16a34a;font-size:13px;"></i>
                                        <div>
                                            <p style="font-size:11px;font-weight:800;color:#15803d;margin:0;">
                                                Pembayaran Dikonfirmasi
                                            </p>
                                            <p style="font-size:10px;color:#16a34a;margin:0;">
                                                {{ $selected->paid_at?->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                            @endif
                            {{-- ══ END PAYMENT SECTION ══ --}}

                            {{-- Actions --}}
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                @if ($selected->isCancellable())
                                    <button class="btn-cancel"
                                        onclick="document.getElementById('cancelModal').classList.add('open')">
                                        <i class="fas fa-times-circle" style="font-size:12px;"></i>
                                        Batalkan Pesanan
                                    </button>
                                @endif

                                @if ($selected->status === 'completed' && !$selected->customer_rating)
                                    <button class="btn-rate"
                                        onclick="document.getElementById('rateModal').classList.add('open')">
                                        ⭐ Beri Penilaian — {{ $selected->mitra?->name }}
                                    </button>
                                @endif
                            </div>

                        </div>{{-- end padding dalam tk-card --}}
                    </div>{{-- end .tk-card --}}

                @endif
                {{-- end @if ($selected) --}}

                {{-- ── EMPTY STATE ── --}}
                @if (!$selected && $activeOrders->isEmpty() && $historyOrders->isEmpty())
                    <div class="tk-card" style="padding:48px 20px;text-align:center;">
                        <div style="font-size:48px;margin-bottom:12px;">📭</div>
                        <p style="font-weight:900;font-size:15px;color:#334155;margin:0;">Belum ada pesanan</p>
                        <p style="font-size:12px;color:#94a3b8;margin:6px 0 16px;">Buat pesanan pertama Anda sekarang!</p>
                        <a href="{{ route('customer.order') }}"
                            style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;
                                   background:#16a34a;color:white;border-radius:10px;font-size:12px;
                                   font-weight:700;text-decoration:none;">
                            <i class="fas fa-plus" style="font-size:10px;"></i> Buat Pesanan
                        </a>
                    </div>
                @endif

                {{-- ── RIWAYAT ── --}}
                @if ($historyOrders->isNotEmpty())
                    <div>
                        <p style="font-size:11px;font-weight:800;color:#334155;margin:0 0 8px 2px;">
                            📋 Riwayat Pengiriman
                        </p>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach ($historyOrders as $o)
                                <a href="{{ route('customer.order.show', $o->id) }}" class="hist-item">
                                    <div
                                        style="width:36px;height:36px;border-radius:10px;background:#f8fafc;
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:16px;flex-shrink:0;">
                                        {{ $o->service_type === 'kirim_barang' ? '📦' : ($o->service_type === 'antar_orang' ? '🛵' : '🍜') }}
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-weight:700;font-size:11px;color:#1e293b;margin:0;">
                                            {{ $o->order_code }}
                                        </p>
                                        <p
                                            style="font-size:10px;color:#94a3b8;margin:1px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $o->pickup_address }} → {{ $o->destination_address }}
                                        </p>
                                        <p style="font-size:10px;color:#cbd5e1;margin:1px 0 0;">
                                            {{ $o->created_at->format('d M Y · H:i') }}
                                        </p>
                                    </div>
                                    <div style="text-align:right;flex-shrink:0;">
                                        <p style="font-weight:900;font-size:11px;color:#16a34a;margin:0;">
                                            Rp {{ number_format($o->total_fare, 0, ',', '.') }}
                                        </p>
                                        @php
                                            $bc = match ($o->status) {
                                                'completed' => 'bdg-green',
                                                'cancelled' => 'bdg-red',
                                                'waiting' => 'bdg-yellow',
                                                default => 'bdg-blue',
                                            };
                                        @endphp
                                        <span class="bdg {{ $bc }}" style="margin-top:3px;font-size:9px;">
                                            {{ $o->status_badge['label'] }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div style="margin-top:10px;">{{ $historyOrders->links() }}</div>
                    </div>
                @endif

            </div>
            {{-- ══ END KOLOM KIRI ══ --}}

            {{-- ╔══════════════════════════════════════════╗
                 ║  KOLOM KANAN — sidebar info             ║
                 ╚══════════════════════════════════════════╝ --}}
            <div style="display:flex;flex-direction:column;gap:10px;min-width:0;">

                {{-- Pesanan Aktif Lain --}}
                @if ($activeOrders->count() > 1)
                    <div class="side-card">
                        <div class="side-label">
                            <span
                                style="width:6px;height:6px;border-radius:50%;background:#16a34a;
                                         animation:blink 1.2s infinite;display:inline-block;"></span>
                            Pesanan Aktif ({{ $activeOrders->count() }})
                        </div>
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @foreach ($activeOrders as $o)
                                <a href="?order_id={{ $o->id }}"
                                    class="active-order-row {{ $selected?->id === $o->id ? 'selected' : '' }}">
                                    <span style="font-size:14px;flex-shrink:0;">
                                        {{ $o->service_type === 'kirim_barang' ? '📦' : ($o->service_type === 'antar_orang' ? '🛵' : '🍜') }}
                                    </span>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-weight:700;font-size:11px;color:#334155;margin:0;">
                                            {{ $o->order_code }}
                                        </p>
                                        <p
                                            style="font-size:10px;color:#94a3b8;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $o->pickup_address }}
                                        </p>
                                    </div>
                                    @php
                                        $bc2 = match ($o->status) {
                                            'completed' => 'bdg-green',
                                            'cancelled' => 'bdg-red',
                                            'waiting' => 'bdg-yellow',
                                            default => 'bdg-blue',
                                        };
                                    @endphp
                                    <span class="bdg {{ $bc2 }}" style="font-size:9px;flex-shrink:0;">
                                        {{ $o->status_badge['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Info Pesanan --}}
                @if ($selected)
                    <div class="side-card">
                        <div class="side-label">ℹ️ Info Pesanan</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach ([['Kode', $selected->order_code], ['Layanan', $selected->service_label], ['Kendaraan', $selected->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil'], ['Jarak', $selected->distance_km ? $selected->distance_km . ' km' : '-'], ['Bayar', strtoupper($selected->payment_method)]] as [$l, $v])
                                <div style="display:flex;justify-content:space-between;gap:8px;align-items:flex-start;">
                                    <span style="font-size:10px;color:#94a3b8;flex-shrink:0;">{{ $l }}</span>
                                    <span
                                        style="font-size:10px;font-weight:700;color:#334155;text-align:right;word-break:break-all;">{{ $v }}</span>
                                </div>
                            @endforeach
                            <div
                                style="border-top:1px solid #f1f5f9;padding-top:6px;
                                        display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:11px;font-weight:700;color:#475569;">Total</span>
                                <span style="font-size:14px;font-weight:900;color:#16a34a;">
                                    Rp {{ number_format($selected->total_fare, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            {{-- ══ END KOLOM KANAN ══ --}}

        </div>
        {{-- ══ END TRACK-GRID ══ --}}

    </div>
    {{-- ══ END PAGE-WRAP ══ --}}


    {{-- ══════════════════════════════════════════════════════
         FLOATING CHAT
    ══════════════════════════════════════════════════════ --}}
    @if ($chatEnabled)
        <div id="chatBox">
            {{-- Header --}}
            <div style="background:#16a34a;padding:8px 12px;display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <div
                    style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.2);
                            display:flex;align-items:center;justify-content:center;
                            font-weight:900;color:white;font-size:11px;flex-shrink:0;">
                    {{ strtoupper(substr($selected->mitra->name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <p
                        style="font-weight:800;color:white;font-size:12px;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $selected->mitra->name }}
                    </p>
                    <p style="font-size:10px;color:rgba(255,255,255,.7);margin:0;">
                        ● {{ $selected->isRide() ? 'Driver' : 'Mitra' }} Anda
                    </p>
                </div>
                <button onclick="toggleChat()"
                    style="background:none;border:none;color:rgba(255,255,255,.8);cursor:pointer;font-size:14px;padding:4px;flex-shrink:0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Messages --}}
            <div id="chatMessages"></div>
            {{-- Input --}}
            <div style="padding:8px;border-top:1px solid #e2e8f0;display:flex;gap:6px;background:white;flex-shrink:0;">
                <input type="text" id="chatInput" placeholder="Ketik pesan..." maxlength="500"
                    style="flex:1;border:1.5px solid #e2e8f0;border-radius:10px;
                           padding:7px 10px;font-size:12px;outline:none;font-family:inherit;"
                    onkeydown="if(event.key==='Enter')sendMessage()" onfocus="this.style.borderColor='#16a34a'"
                    onblur="this.style.borderColor='#e2e8f0'">
                <button onclick="sendMessage()"
                    style="width:34px;height:34px;background:#16a34a;color:white;border:none;
                           border-radius:10px;cursor:pointer;display:flex;align-items:center;
                           justify-content:center;flex-shrink:0;">
                    <i class="fas fa-paper-plane" style="font-size:11px;"></i>
                </button>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════
         CANCEL MODAL
    ══════════════════════════════════════════════════════ --}}
    @if ($selected && $selected->isCancellable())
        <div id="cancelModal" class="modal-backdrop">
            <div class="modal-box">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="font-size:40px;margin-bottom:8px;">😔</div>
                    <h3 style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">Batalkan Pesanan?</h3>
                    <p style="font-size:11px;color:#94a3b8;margin:5px 0 0;">
                        <strong style="color:#475569;">{{ $selected->order_code }}</strong>
                        tidak dapat dikembalikan setelah dibatalkan.
                    </p>
                </div>
                <form method="POST" action="{{ route('customer.order.cancel', $selected->id) }}"
                    onsubmit="return konfirmasiCancel()">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label style="font-size:11px;font-weight:700;color:#475569;display:block;margin-bottom:5px;">
                            Alasan Pembatalan <span style="color:#ef4444;">*</span>
                        </label>
                        <select name="cancel_reason" id="cancelReason" required
                            style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;
                                   padding:9px 12px;font-size:12px;outline:none;
                                   background:white;font-family:inherit;">
                            <option value="">-- Pilih alasan --</option>
                            <option value="Salah input alamat">Salah input alamat</option>
                            <option value="Ingin mengubah pesanan">Ingin mengubah pesanan</option>
                            <option value="Mitra terlalu lama">Mitra / driver terlalu lama</option>
                            <option value="Keadaan darurat">Keadaan darurat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:8px;">
                        <button type="button" onclick="document.getElementById('cancelModal').classList.remove('open')"
                            style="flex:1;padding:10px;border-radius:10px;border:1.5px solid #e2e8f0;
                                   background:white;color:#64748b;font-size:12px;font-weight:700;cursor:pointer;">
                            Kembali
                        </button>
                        <button type="submit"
                            style="flex:1;padding:10px;border-radius:10px;border:none;
                                   background:#ef4444;color:white;font-size:12px;font-weight:700;cursor:pointer;">
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    {{-- ══════════════════════════════════════════════════════
         RATE MODAL
    ══════════════════════════════════════════════════════ --}}
    @if ($selected && $selected->status === 'completed' && !$selected->customer_rating)
        <div id="rateModal" class="modal-backdrop">
            <div class="modal-box">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="font-size:40px;margin-bottom:8px;">⭐</div>
                    <h3 style="font-size:15px;font-weight:900;color:#0f172a;margin:0;">Beri Penilaian</h3>
                    <p style="font-size:11px;color:#94a3b8;margin:5px 0 0;">
                        Pengalaman Anda dengan
                        <strong style="color:#475569;">{{ $selected->mitra?->name }}</strong>?
                    </p>
                </div>
                <form method="POST" action="{{ route('customer.order.rate', $selected->id) }}">
                    @csrf
                    <div style="display:flex;justify-content:center;gap:8px;margin-bottom:14px;">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-btn"
                                data-val="{{ $i }}"
                                style="font-size:30px;background:none;border:none;cursor:pointer;
                                       color:#e2e8f0;transition:color .15s,transform .15s;padding:0;">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5">
                    <textarea name="review" rows="2" placeholder="Tulis ulasan (opsional)..."
                        style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;
                               padding:9px 12px;font-size:12px;outline:none;resize:none;
                               font-family:inherit;box-sizing:border-box;margin-bottom:12px;"></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="button" onclick="document.getElementById('rateModal').classList.remove('open')"
                            style="flex:1;padding:10px;border-radius:10px;border:1.5px solid #e2e8f0;
                                   background:white;color:#64748b;font-size:12px;font-weight:700;cursor:pointer;">
                            Nanti
                        </button>
                        <button type="submit"
                            style="flex:1;padding:10px;border-radius:10px;border:none;
                                   background:#16a34a;color:white;font-size:12px;font-weight:700;cursor:pointer;">
                            Kirim ⭐
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    {{-- Rating --}}
    @if ($selected && $selected->status === 'completed' && !$selected->customer_rating)
        <script>
            function setRating(val) {
                document.getElementById('ratingInput').value = val;
                document.querySelectorAll('.star-btn').forEach(b => {
                    const v = parseInt(b.dataset.val);
                    b.style.color = v <= val ? '#f59e0b' : '#e2e8f0';
                    b.style.transform = v <= val ? 'scale(1.1)' : 'scale(1)';
                });
            }
            setRating(5);
            document.getElementById('rateModal')?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        </script>
    @endif

    {{-- Cancel --}}
    @if ($selected && $selected->isCancellable())
        <script>
            function konfirmasiCancel() {
                const a = document.getElementById('cancelReason').value;
                if (!a) {
                    alert('Pilih alasan pembatalan.');
                    return false;
                }
                return confirm('Batalkan {{ $selected->order_code }}?\nAlasan: ' + a);
            }
            document.getElementById('cancelModal')?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        </script>
    @endif

    {{-- Chat --}}
    @if ($chatEnabled)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            const CHAT_ORDER_ID = {{ $selected->id }};
            const CHAT_AUTH_ID = {{ auth()->id() }};
            let chatOpen = false,
                unreadCount = 0,
                pusher, channel;

            function initPusher() {
                pusher = new Pusher('50f762dbbea60782d00e', {
                    cluster: 'ap1',
                    forceTLS: true
                });
                channel = pusher.subscribe('order-chat.' + CHAT_ORDER_ID);
                channel.bind('new-message', function(data) {
                    if (data.sender_id !== CHAT_AUTH_ID) {
                        appendMsg(data, false);
                        if (!chatOpen) {
                            unreadCount++;
                            updateBadge();
                        }
                    }
                });
            }

            function checkUnread() {
                fetch('/chat/' + CHAT_ORDER_ID + '/unread-count', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.unread > 0) {
                            unreadCount = d.unread;
                            updateBadge();
                        }
                    })
                    .catch(() => {});
            }

            function toggleChat() {
                chatOpen = !chatOpen;
                const box = document.getElementById('chatBox');
                if (chatOpen) {
                    box.classList.add('open');
                    unreadCount = 0;
                    updateBadge();
                    loadMsgs();
                } else {
                    box.classList.remove('open');
                }
            }

            function loadMsgs() {
                fetch('/chat/' + CHAT_ORDER_ID + '/messages', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(msgs => {
                        const c = document.getElementById('chatMessages');
                        c.innerHTML = '';
                        if (!msgs.length) {
                            c.innerHTML =
                                '<div style="text-align:center;color:#94a3b8;font-size:11px;padding:20px 0;">Belum ada pesan 👋</div>';
                            return;
                        }
                        msgs.forEach(m => appendMsg(m, m.is_mine));
                        scrollBot();
                    })
                    .catch(() => {
                        document.getElementById('chatMessages').innerHTML =
                            '<div style="text-align:center;color:#ef4444;font-size:11px;padding:20px 0;">Gagal memuat.</div>';
                    });
            }

            function appendMsg(data, isMine) {
                const c = document.getElementById('chatMessages');
                const w = document.createElement('div');
                w.style.cssText = 'display:flex;flex-direction:column;align-items:' + (isMine ? 'flex-end' : 'flex-start');
                w.innerHTML = `${isMine ? '' : `<div class="cb-name">${esc(data.sender_name)}</div>`}
                    <div class="cb ${isMine ? 'mine' : 'theirs'}">
                        ${esc(data.message)}<div class="cb-time">${data.time}</div>
                    </div>`;
                c.appendChild(w);
                scrollBot();
            }

            function sendMessage() {
                const inp = document.getElementById('chatInput');
                const msg = inp.value.trim();
                if (!msg) return;
                inp.value = '';
                inp.disabled = true;
                fetch('/chat/' + CHAT_ORDER_ID + '/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            message: msg
                        }),
                    })
                    .then(r => r.json())
                    .then(d => {
                        appendMsg(d, true);
                        inp.disabled = false;
                        inp.focus();
                    })
                    .catch(() => {
                        inp.disabled = false;
                        inp.value = msg;
                        alert('Gagal mengirim.');
                    });
            }

            function scrollBot() {
                const el = document.getElementById('chatMessages');
                if (el) el.scrollTop = el.scrollHeight;
            }

            function updateBadge() {
                const b = document.getElementById('chatUnreadBadge');
                if (!b) return;
                if (unreadCount > 0) {
                    b.textContent = unreadCount > 9 ? '9+' : unreadCount;
                    b.classList.add('show');
                } else {
                    b.classList.remove('show');
                }
            }

            function esc(s) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(s));
                return d.innerHTML;
            }

            document.addEventListener('DOMContentLoaded', () => {
                initPusher();
                checkUnread();
            });
        </script>
    @endif
@endpush
