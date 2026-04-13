@extends('layouts.app')
@section('title', 'Dashboard Mitra')

@section('sidebar-nav')
  <a href="{{ route('mitra.dashboard') }}" class="sidebar-link active">
    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
  </a>
  <a href="{{ route('mitra.earnings') }}" class="sidebar-link">
    <i class="fas fa-wallet w-5"></i> Pendapatan
  </a>
  <a href="{{ route('mitra.history') }}" class="sidebar-link">
    <i class="fas fa-history w-5"></i> Riwayat Pesanan
  </a>
  <a href="{{ route('mitra.ratings') }}" class="sidebar-link">
    <i class="fas fa-star w-5"></i>  Rating & Ulasan
  </a>
  
@endsection

@section('bottom-nav')
  <a href="{{ route('mitra.dashboard') }}" class="bnav-item active">
    <i class="fas fa-home text-xl"></i><span>Dashboard</span>
  </a>
  <a href="{{ route('mitra.earnings') }}" class="bnav-item">
    <i class="fas fa-wallet text-xl"></i><span>Pendapatan</span>
  </a>
  <a href="{{ route('mitra.history') }}" class="bnav-item">
    <i class="fas fa-history text-xl"></i><span>Riwayat</span>
  </a>
  <a href="{{ route('mitra.profile') }}" class="bnav-item">
    <i class="fas fa-user text-xl"></i><span>Profil</span>
  </a>
@endsection

@push('styles')
<style>
/* ── Toggle Online ───────────────────────────────────── */
.toggle-track {
    width: 56px; height: 28px; border-radius: 99px;
    position: relative; cursor: pointer;
    transition: background .3s ease;
    border: none; outline: none; flex-shrink: 0;
}
.toggle-track.is-online  { background: #4ade80; }
.toggle-track.is-offline { background: rgba(255,255,255,.3); }
.toggle-dot {
    position: absolute; top: 3px;
    width: 22px; height: 22px;
    background: white; border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
    transition: left .3s ease;
}
.toggle-dot.is-online  { left: 31px; }
.toggle-dot.is-offline { left: 3px; }
.status-label {
    font-size: 13px; font-weight: 700;
    padding: 6px 14px; border-radius: 12px;
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.2);
}
.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.status-dot.online  { background: #4ade80; }
.status-dot.offline { background: #f87171; }

/* ── Chat box ────────────────────────────────────────── */
#chatBox {
    display: none; flex-direction: column;
    border-radius: 14px; overflow: hidden;
    border: 1.5px solid #bfdbfe; background: white;
    margin-top: 12px;
}
#chatBox.open { display: flex; }
#chatMessages {
    flex: 1; overflow-y: auto; padding: 12px;
    background: #f8fafc; display: flex;
    flex-direction: column; gap: 8px;
    min-height: 140px; max-height: 240px;
}
.chat-bubble {
    max-width: 80%; padding: 8px 12px;
    border-radius: 16px; font-size: 13px;
    line-height: 1.4; word-break: break-word;
}
.chat-bubble.mine   { align-self:flex-end; background:#16a34a; color:white; border-bottom-right-radius:4px; }
.chat-bubble.theirs { align-self:flex-start; background:white; color:#1e293b; border:1px solid #e2e8f0; border-bottom-left-radius:4px; }
.chat-time { font-size:10px; opacity:.6; margin-top:3px; text-align:right; }
.chat-bubble.theirs .chat-time { text-align:left; }
.chat-name { font-size:10px; font-weight:700; opacity:.7; margin-bottom:2px; }

/* FIX BUG 1: Ganti dari #chatUnreadBadge ke .chat-unread-badge */
.chat-btn-wrap { position:relative; display:inline-block; }
.chat-unread-badge {
    display:none; position:absolute; top:-4px; right:-4px;
    background:#ef4444; color:white; font-size:10px; font-weight:800;
    min-width:16px; height:16px; border-radius:8px;
    align-items:center; justify-content:center;
    padding:0 3px; border:2px solid white;
}
.chat-unread-badge.show { display:flex; }

/* ── Progress flow bar ───────────────────────────────── */
.flow-bar { display:flex; align-items:center; gap:0; }
.flow-step-wrap { display:flex; flex-direction:column; align-items:center; gap:2px; }
.flow-dot {
    width:8px; height:8px; border-radius:50%;
    background:#e2e8f0; transition:background .3s; flex-shrink:0;
}
.flow-dot.done    { background:#16a34a; }
.flow-dot.current { background:#16a34a; animation:pdot 1.5s infinite; }
@keyframes pdot {
    0%,100%{ box-shadow:0 0 0 0 rgba(22,163,74,.4); }
    50%    { box-shadow:0 0 0 5px rgba(22,163,74,0); }
}
.flow-line { flex:1; height:2px; background:#e2e8f0; }
.flow-line.done { background:#16a34a; }
.flow-lbl { font-size:8px; color:#cbd5e1; font-weight:600; margin-top:2px; text-align:center; }
.flow-lbl.done { color:#16a34a; }
.flow-lbl.current { color:#16a34a; font-weight:800; }

@media (min-width: 640px)  { .stat-num { font-size: 1.6rem !important; } }
@media (min-width: 1024px) { .stat-num { font-size: 1.9rem !important; } }

/* ── Cancel modal ────────────────────────────────────── */
.modal-backdrop {
    display:none; position:fixed; inset:0; z-index:9000;
    background:rgba(0,0,0,.45);
    align-items:flex-end; justify-content:center;
}
@media(min-width:640px){ .modal-backdrop{ align-items:center; padding:16px; } }
.modal-backdrop.open { display:flex; }
.modal-box {
    background:white; width:100%; max-width:360px;
    border-radius:20px 20px 0 0; padding:20px 16px;
    animation:slideup .25s ease;
}
@media(min-width:640px){ .modal-box{ border-radius:20px; } }
@keyframes slideup { from{transform:translateY(40px);opacity:0} to{transform:translateY(0);opacity:1} }
</style>
@endpush



@section('content')
@if(!Auth::user()->is_active || Auth::user()->status === 'suspended')
    <div class="mx-4 mt-4 px-4 py-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🚫</span>
            <div>
                <p class="font-bold text-red-700">Akun Anda Sedang Disuspend</p>
                <p class="text-sm text-red-500 mt-0.5">
                    Anda tidak dapat menerima order saat ini.
                    Silakan hubungi admin untuk informasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>
@endif
<div class="w-full max-w-5xl mx-auto px-0 sm:px-2 md:px-4 space-y-4 sm:space-y-5 pb-8">

  {{-- ── HEADER BANNER ─────────────────────────────────── --}}
  <div class="relative overflow-hidden text-white
              rounded-none sm:rounded-2xl md:rounded-3xl
              p-4 sm:p-6 md:p-8"
       style="background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 50%,#3b82f6 100%)">
    <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full pointer-events-none"
         style="background:rgba(255,255,255,.07)"></div>
    <div class="absolute -bottom-8 -left-8 w-28 h-28 rounded-full pointer-events-none"
         style="background:rgba(255,255,255,.04)"></div>

    <div class="relative flex items-start justify-between gap-4 flex-wrap mb-5">
      <div class="min-w-0">
        <p class="text-blue-200 text-xs sm:text-sm font-medium">Selamat datang,</p>
        <h1 class="font-black leading-tight mt-0.5 text-lg sm:text-xl md:text-2xl lg:text-3xl">
          {{ $user->name }} 👋
        </h1>
        <p class="text-blue-200 text-xs sm:text-sm mt-1 truncate">
          {{ $profile?->vehicle_label ?? 'Kendaraan belum diisi' }}
        </p>
        <div class="flex items-center gap-2 mt-3 flex-wrap">
          <div class="status-label" id="statusLabel">
            <span class="status-dot {{ $profile?->is_online ? 'online' : 'offline' }}" id="statusDot"></span>
            <span id="statusText">{{ $profile?->is_online ? 'Online' : 'Offline' }}</span>
          </div>
          <div class="px-3 py-1.5 rounded-xl text-xs sm:text-sm font-bold"
               style="background:rgba(255,255,255,.2)">
            ⭐ {{ $profile?->rating ?? '5.0' }}
          </div>
        </div>
      </div>
      <div class="text-right flex-shrink-0">
        <p class="text-xs text-blue-200 mb-2 font-medium">Status Online</p>
        <button id="onlineToggle" onclick="toggleOnline()"
                class="toggle-track {{ $profile?->is_online ? 'is-online' : 'is-offline' }}">
          <span id="toggleDot" class="toggle-dot {{ $profile?->is_online ? 'is-online' : 'is-offline' }}"></span>
        </button>
        <p class="text-xs text-blue-200 mt-1.5 whitespace-nowrap" id="toggleHint">
          {{ $profile?->is_online ? 'Ketuk untuk Offline' : 'Ketuk untuk Online' }}
        </p>
      </div>
    </div>

    {{-- Stats row --}}
    <div class="relative grid grid-cols-3 gap-2 sm:gap-3 md:gap-4">
      <div class="rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center"
           style="background:rgba(255,255,255,.15)">
        <p class="stat-num font-black text-xl sm:text-2xl">{{ $todayOrders }}</p>
        <p class="text-xs text-blue-200 mt-0.5 leading-tight">Order<br class="sm:hidden"> Hari Ini</p>
      </div>
      <div class="rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center"
           style="background:rgba(255,255,255,.15)">
        <p class="stat-num font-black text-xl sm:text-2xl">{{ $profile?->total_trips ?? $totalCompleted }}</p>
        <p class="text-xs text-blue-200 mt-0.5 leading-tight">Total<br class="sm:hidden"> Trip</p>
      </div>
      <div class="rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center"
           style="background:rgba(255,255,255,.15)">
        <p class="stat-num font-black text-lg sm:text-xl">
          Rp {{ number_format($todayEarning / 1000, 0, ',', '.') }}K
        </p>
        <p class="text-xs text-blue-200 mt-0.5 leading-tight">Hari<br class="sm:hidden"> Ini</p>
      </div>
    </div>
  </div>

  {{-- ── FLASH MESSAGES ────────────────────────────────── --}}
  @if(session('success'))
  <div class="mx-1 sm:mx-0 p-3 rounded-xl text-sm font-semibold flex items-center gap-2"
       style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="mx-1 sm:mx-0 p-3 rounded-xl text-sm font-semibold flex items-center gap-2"
       style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
  </div>
  @endif

  {{-- ── MAIN CONTENT GRID ─────────────────────────────── --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 px-0 sm:px-1">

    <div class="lg:col-span-2 space-y-4 sm:space-y-5">

      {{-- ══════════════════════════════════════════════════
           PESANAN AKTIF — dengan tombol bertahap
      ══════════════════════════════════════════════════ --}}
      @forelse($activeOrders as $activeOrder)
      @php
        $isRide = $activeOrder->isRide();

        // Flow steps sesuai service type
        if ($isRide) {
            $flowSteps  = ['waiting','accepted','on_the_way','picked_up','arrived','completed'];
            $flowLabels = ['Tunggu','Terima','Menuju','Jemput','Tiba','Selesai'];
        } else {
            $flowSteps  = ['waiting','accepted','picking_up','in_progress','delivered','completed'];
            $flowLabels = ['Tunggu','Terima','Pickup','Jalan','Kirim','Selesai'];
        }

        // FIX BUG 2: array_search bisa return false jika status tidak dikenali
        $currentIdx = array_search($activeOrder->status, $flowSteps);
        $currentIdx = ($currentIdx === false) ? 0 : $currentIdx;

        $nextLabel  = $activeOrder->getNextButtonLabel();

        $actionIcon = match($activeOrder->status) {
            'waiting'     => 'fa-check-circle',
            'accepted'    => 'fa-route',
            'on_the_way'  => 'fa-user-check',
            'picked_up'   => 'fa-play-circle',
            'picking_up'  => 'fa-box-open',
            'in_progress' => 'fa-flag-checkered',
            'delivered'   => 'fa-check-double',
            'arrived'     => 'fa-flag-checkered',
            default       => 'fa-arrow-right',
        };
      @endphp

      <div class="card p-4 sm:p-5 border-2" style="border-color:#3b82f6;background:#eff6ff">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
          <h2 class="font-black text-slate-800 flex items-center gap-2 text-sm sm:text-base">
            <span class="w-2 h-2 rounded-full animate-pulse flex-shrink-0" style="background:#3b82f6"></span>
            {{ $isRide ? 'Perjalanan Aktif' : 'Pengiriman Aktif' }}
          </h2>
          <span class="badge badge-info text-xs sm:text-sm">
            {{ $activeOrder->status_badge['label'] }}
          </span>
        </div>

        <p class="text-xs text-slate-400 font-semibold mb-3">
          {{ $activeOrder->order_code }}
          · {{ $activeOrder->service_label }}
          @if($activeOrder->distance_km) · {{ $activeOrder->distance_km }} km @endif
        </p>

        {{-- Progress bar --}}
        <div class="bg-white rounded-xl p-3 mb-3">
          <p class="text-xs font-bold text-slate-400 mb-2">Progress Status</p>
          <div class="flow-bar">
            @foreach($flowSteps as $fi => $fStep)
            <div class="flow-step-wrap">
              <div class="flow-dot
                {{ $currentIdx > $fi ? 'done' : ($currentIdx === $fi ? 'current' : '') }}">
              </div>
              <span class="flow-lbl
                {{ $currentIdx > $fi ? 'done' : ($currentIdx === $fi ? 'current' : '') }}">
                {{ $flowLabels[$fi] }}
              </span>
            </div>
            @if(!$loop->last)
            <div class="flow-line {{ $currentIdx > $fi ? 'done' : '' }}"></div>
            @endif
            @endforeach
          </div>
        </div>

        {{-- Rute --}}
        <div class="bg-white rounded-xl p-3 sm:p-4 space-y-2 mb-3">
          <div class="flex items-start gap-2">
            <span class="w-2.5 h-2.5 rounded-full mt-1 flex-shrink-0" style="background:#16a34a"></span>
            <p class="text-xs sm:text-sm text-slate-700">{{ $activeOrder->pickup_address }}</p>
          </div>
          <div class="border-l-2 border-dashed border-slate-200 ml-1 pl-3 py-0.5">
            <p class="text-xs text-slate-400">
              {{ $activeOrder->distance_km ?? '–' }} km
              · Rp {{ number_format($activeOrder->mitra_earning, 0, ',', '.') }}
            </p>
          </div>
          <div class="flex items-start gap-2">
            <span class="w-2.5 h-2.5 rounded-full mt-1 flex-shrink-0" style="background:#ef4444"></span>
            <p class="text-xs sm:text-sm text-slate-700">{{ $activeOrder->destination_address }}</p>
          </div>
        </div>

        {{-- Customer row --}}
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center
                        font-black text-sm text-blue-600 flex-shrink-0">
              {{ strtoupper(substr($activeOrder->customer?->name ?? 'C', 0, 1)) }}
            </div>
            <span class="text-xs sm:text-sm text-slate-700 font-semibold">
              {{ $activeOrder->customer?->name ?? '–' }}
            </span>
          </div>
          <div class="flex items-center gap-2">
            @if($activeOrder->customer?->phone)
            <a href="tel:{{ $activeOrder->customer->phone }}"
               class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm
                      border border-slate-200 hover:bg-slate-50 transition"
               style="color:#16a34a" title="Telepon">
              <i class="fas fa-phone text-xs"></i>
            </a>
            @endif
            @php
              $chatStatuses = $isRide
                ? ['accepted','on_the_way','picked_up','arrived']
                : ['accepted','picking_up','in_progress','delivered'];
            @endphp
            @if(in_array($activeOrder->status, $chatStatuses))
            <div class="chat-btn-wrap">
              <button onclick="toggleChat({{ $activeOrder->id }})"
                      class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-sm
                             border border-slate-200 hover:bg-slate-50 transition"
                      style="color:#16a34a" title="Chat">
                <i class="fas fa-comment text-xs"></i>
              </button>
              {{-- FIX BUG 1: Gunakan class .chat-unread-badge, bukan ID tanpa class --}}
              <span id="chatUnreadBadge_{{ $activeOrder->id }}" class="chat-unread-badge"></span>
            </div>
            @endif
          </div>
        </div>

        {{-- Embedded chat box --}}
        <div id="chatBox_{{ $activeOrder->id }}" class="mb-3" style="display:none;flex-direction:column;border-radius:14px;overflow:hidden;border:1.5px solid #bfdbfe;background:white;">
          <div style="background:#16a34a;padding:10px 14px;display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <div style="width:28px;height:28px;background:rgba(255,255,255,.2);border-radius:50%;
                        display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:11px;">
              {{ strtoupper(substr($activeOrder->customer?->name ?? 'C', 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0;">
              <p style="font-weight:800;color:white;font-size:12px;margin:0;">
                {{ $activeOrder->customer?->name ?? 'Customer' }}
              </p>
              <p style="font-size:10px;color:rgba(255,255,255,.75);margin:0;">● Customer Anda</p>
            </div>
            <button onclick="toggleChat({{ $activeOrder->id }})"
                    style="background:none;border:none;color:rgba(255,255,255,.8);cursor:pointer;font-size:14px;padding:3px;flex-shrink:0;">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div id="chatMessages_{{ $activeOrder->id }}"
               style="flex:1;overflow-y:auto;padding:10px;background:#f8fafc;
                      display:flex;flex-direction:column;gap:6px;
                      min-height:120px;max-height:220px;">
          </div>
          <div style="padding:8px;border-top:1px solid #e2e8f0;display:flex;gap:6px;background:white;flex-shrink:0;">
            <input type="text" id="chatInput_{{ $activeOrder->id }}"
                   placeholder="Ketik pesan..." maxlength="500"
                   style="flex:1;border:1.5px solid #e2e8f0;border-radius:10px;
                          padding:7px 10px;font-size:12px;outline:none;"
                   onkeydown="if(event.key==='Enter')sendMessage({{ $activeOrder->id }})"
                   onfocus="this.style.borderColor='#16a34a'"
                   onblur="this.style.borderColor='#e2e8f0'">
            <button onclick="sendMessage({{ $activeOrder->id }})"
                    style="width:32px;height:32px;background:#16a34a;color:white;border:none;
                           border-radius:10px;cursor:pointer;display:flex;align-items:center;
                           justify-content:center;flex-shrink:0;">
              <i class="fas fa-paper-plane" style="font-size:11px;"></i>
            </button>
          </div>
        </div>

        {{-- ── ACTION BUTTON: berganti otomatis sesuai status ── --}}
        @if($nextLabel)
        <form method="POST"
              action="{{ route('mitra.order.update-status', $activeOrder->id) }}"
              onsubmit="return confirm('{{ addslashes($nextLabel) }}?\n\nLanjutkan?')">
          @csrf
          @method('PATCH')
          <button type="submit"
                  class="w-full text-white font-bold py-3 rounded-xl transition-all
                         flex items-center justify-center gap-2 text-xs sm:text-sm active:scale-95"
                  style="background:#16a34a">
            <i class="fas {{ $actionIcon }}"></i> {{ $nextLabel }}
          </button>
        </form>
        @else
        <div class="text-center py-2 rounded-xl text-xs font-bold"
             style="background:#f0fdf4;color:#16a34a">
          ✅ Pesanan Selesai
        </div>
        @endif

        {{-- Cancel button --}}
        @if($activeOrder->isCancellable())
        <button class="w-full mt-2 py-2 border-2 font-bold rounded-xl text-xs hover:bg-red-50 transition"
                style="border-color:#fca5a5;color:#dc2626"
                onclick="openCancel({{ $activeOrder->id }}, '{{ addslashes($activeOrder->order_code) }}')">
          <i class="fas fa-times text-xs"></i> Batalkan Pesanan
        </button>
        @endif

      </div>
      @empty
      {{-- tidak ada order aktif — tampilkan section kosong --}}
      @endforelse

      {{-- ══════════════════════════════════════════════════
           PESANAN MASUK (waiting orders)
      ══════════════════════════════════════════════════ --}}
      @if($profile?->is_online && $pendingOrders->isNotEmpty())
      <div>
        <h2 class="font-black text-slate-800 mb-3 flex items-center gap-2 text-sm sm:text-base">
          🔔 Pesanan Masuk
          <span class="badge badge-warning">{{ $pendingOrders->count() }}</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          @foreach($pendingOrders as $pending)
          <div class="card p-4 sm:p-5 border-2 relative overflow-hidden"
               style="border-color:#fcd34d;background:#fffbeb">
            <div class="absolute top-3 right-4 text-xl animate-bounce">🔔</div>
            <span class="badge badge-warning mb-2">Pesanan Baru</span>
            <p class="font-bold text-slate-800 mt-1 text-sm">
              {{ $pending->service_label }}
              · {{ $pending->vehicle_type === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}
            </p>
            <div class="text-xs mt-2 space-y-1" style="color:#92400e">
              <p class="truncate">📍 {{ $pending->pickup_address }}</p>
              <p class="truncate">🎯 {{ $pending->destination_address }}</p>
            </div>
            <div class="flex items-center gap-2 mt-2 text-xs font-bold flex-wrap" style="color:#b45309">
              @if($pending->distance_km)<span>📏 {{ $pending->distance_km }} km</span>@endif
              @if($pending->item_weight)<span>⚖️ {{ $pending->item_weight }} kg</span>@endif
              <span class="text-sm font-black" style="color:#78350f">
                Rp {{ number_format($pending->mitra_earning ?? ($pending->total_fare * 0.9), 0, ',', '.') }}
              </span>
            </div>
            <div class="flex gap-2 mt-4">
              {{-- Tolak --}}
              <form method="POST" action="{{ route('mitra.order.cancel', $pending->id) }}" class="flex-1">
                @csrf
                <input type="hidden" name="cancel_reason" value="Ditolak mitra">
                <button type="submit"
                        class="w-full py-2.5 border-2 font-bold rounded-xl text-xs hover:bg-red-50 transition"
                        style="border-color:#f87171;color:#dc2626"
                        onclick="return confirm('Tolak pesanan ini?')">
                  Tolak
                </button>
              </form>
              {{-- Terima — pakai route accept --}}
              <form method="POST" action="{{ route('mitra.order.accept', $pending->id) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full py-2.5 text-white font-bold rounded-xl text-xs active:scale-95"
                        style="background:#16a34a">
                  Terima ✓
                </button>
              </form>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      @elseif(!$profile?->is_online)
      <div class="card p-8 sm:p-10 text-center border-2 border-dashed border-slate-200">
        <div class="text-4xl sm:text-5xl mb-3">😴</div>
        <p class="font-bold text-slate-600 sm:text-lg">Anda sedang Offline</p>
        <p class="text-slate-400 text-xs sm:text-sm mt-1">
          Aktifkan status Online untuk mulai menerima pesanan
        </p>
        <button onclick="toggleOnline()"
                class="mt-4 text-white font-bold px-6 py-2.5 rounded-xl text-sm"
                style="background:#16a34a">
          Aktifkan Sekarang
        </button>
      </div>

      @elseif($activeOrders->isEmpty())
      <div class="card p-8 sm:p-10 text-center">
        <div class="text-4xl sm:text-5xl mb-3">👀</div>
        <p class="font-bold text-slate-600 sm:text-lg">Menunggu pesanan masuk...</p>
        <p class="text-slate-400 text-xs sm:text-sm mt-1">Halaman otomatis refresh setiap 30 detik</p>
        <div class="mt-3 flex items-center justify-center gap-2 text-xs text-slate-400">
          <span class="w-2 h-2 rounded-full animate-pulse flex-shrink-0" style="background:#16a34a"></span>
          Online &amp; siap menerima pesanan
        </div>
      </div>
      @endif

      {{-- ── RIWAYAT TERBARU ─────────────────────────────── --}}
      @if($recentOrders->isNotEmpty())
      <div>
        <h2 class="font-black text-slate-800 mb-3 text-sm sm:text-base">📋 Riwayat Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3">
          @foreach($recentOrders->take(6) as $order)
          <div class="card p-3 sm:p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                        text-lg flex-shrink-0" style="background:#f0fdf4">
              {{ $order->service_type === 'kirim_barang' ? '📦' : ($order->service_type === 'antar_orang' ? '🛵' : '🍜') }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-bold text-xs sm:text-sm text-slate-800">{{ $order->order_code }}</p>
              <p class="text-xs text-slate-400 truncate">{{ $order->pickup_address }}</p>
              <p class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->format('d M, H:i') }}</p>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="font-black text-xs sm:text-sm" style="color:#16a34a">
                +Rp {{ number_format($order->mitra_earning ?? 0, 0, ',', '.') }}
              </p>
              <span class="badge {{ $order->status_badge['class'] }} text-xs">
                {{ $order->status_badge['label'] }}
              </span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>{{-- end left col --}}

    {{-- ── RIGHT SIDEBAR ──────────────────────────────────── --}}
    <div class="space-y-4">
      <div class="card p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
          <h2 class="font-black text-slate-800 text-sm sm:text-base">💰 Pendapatan Minggu Ini</h2>
          <a href="{{ route('mitra.earnings') }}" class="text-xs sm:text-sm font-bold" style="color:#16a34a">
            Detail →
          </a>
        </div>
        <p class="font-black leading-none" style="color:#16a34a;font-size:clamp(1.5rem,4vw,2.2rem)">
          Rp {{ number_format($weeklyEarning, 0, ',', '.') }}
        </p>
        <p class="text-xs sm:text-sm text-slate-400 mt-2">
          {{ $totalCompleted }} pesanan selesai total
        </p>
        <div class="grid grid-cols-2 gap-2 mt-4">
          <div class="bg-slate-50 rounded-xl p-3 text-center">
            <p class="font-black text-slate-700 text-base sm:text-lg">{{ $todayOrders }}</p>
            <p class="text-xs text-slate-400 mt-0.5">Hari ini</p>
          </div>
          <div class="bg-slate-50 rounded-xl p-3 text-center">
            <p class="font-black text-slate-700 text-base sm:text-lg">{{ $totalCompleted }}</p>
            <p class="text-xs text-slate-400 mt-0.5">Total trip</p>
          </div>
        </div>
      </div>

      <a href="{{ route('mitra.earnings') }}"
         class="card p-4 flex items-center gap-3 hover:shadow-md transition-all group">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 text-xl"
             style="background:#dcfce7">💳</div>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-slate-800 text-sm">Laporan Pendapatan</p>
          <p class="text-xs text-slate-400 mt-0.5">Lihat rincian per hari / minggu</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-slate-500 transition flex-shrink-0"></i>
      </a>
    </div>

  </div>{{-- end grid --}}
</div>

{{-- ── CANCEL MODAL ────────────────────────────────────── --}}
{{-- FIX BUG 5: Simpan base URL di data-attribute agar tidak hardcoded di JS --}}
<div id="cancelModal"
     class="modal-backdrop"
     data-cancel-base="{{ url('mitra/order') }}">
  <div class="modal-box">
    <div style="text-align:center;margin-bottom:14px;">
      <div style="font-size:36px;margin-bottom:6px;">⚠️</div>
      <h3 style="font-size:14px;font-weight:900;color:#0f172a;margin:0;">Batalkan Pesanan?</h3>
      <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;" id="cancelOrderCode">—</p>
    </div>
    <form method="POST" id="cancelForm" onsubmit="return validateCancel()">
      @csrf
      <div style="margin-bottom:10px;">
        <label style="font-size:11px;font-weight:700;color:#475569;display:block;margin-bottom:4px;">
          Alasan <span style="color:#ef4444;">*</span>
        </label>
        <select name="cancel_reason" id="cancelReason" required
                style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;
                       padding:9px 12px;font-size:12px;outline:none;background:white;">
          <option value="">-- Pilih alasan --</option>
          <option value="Customer tidak respon">Customer tidak respon</option>
          <option value="Lokasi tidak ditemukan">Lokasi tidak ditemukan</option>
          <option value="Keadaan darurat">Keadaan darurat</option>
          <option value="Kendaraan bermasalah">Kendaraan bermasalah</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>
      <div style="display:flex;gap:8px;">
        <button type="button" onclick="closeCancel()"
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

@endsection

@push('scripts')
{{-- ── Online toggle ──────────────────────────────────── --}}
<script>
let isOnline  = {{ $profile?->is_online ? 'true' : 'false' }};
let isLoading = false;

// FIX BUG 4: Simpan timer ke variabel agar bisa di-cancel saat toggle
let autoRefreshTimer = null;
@if($profile?->is_online)
autoRefreshTimer = setTimeout(() => location.reload(), 30000);
@endif

async function toggleOnline() {
    if (isLoading) return;
    isLoading = true;
    const btn  = document.getElementById('onlineToggle');
    const dot  = document.getElementById('toggleDot');
    const hint = document.getElementById('toggleHint');
    const sDot = document.getElementById('statusDot');
    const sTxt = document.getElementById('statusText');
    btn.style.opacity = '0.6'; btn.style.cursor = 'wait';
    try {
        const res = await fetch('{{ route("mitra.toggle-online") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).then(r => r.json());

        isOnline = res.is_online;
        if (isOnline) {
            btn.classList.replace('is-offline','is-online');
            dot.classList.replace('is-offline','is-online');
            sDot.classList.replace('offline','online');
            sTxt.textContent = 'Online';
            hint.textContent = 'Ketuk untuk Offline';
        } else {
            btn.classList.replace('is-online','is-offline');
            dot.classList.replace('is-online','is-offline');
            sDot.classList.replace('online','offline');
            sTxt.textContent = 'Offline';
            hint.textContent = 'Ketuk untuk Online';
        }
        // FIX BUG 4: Batalkan auto-refresh sebelum reload manual
        if (autoRefreshTimer) clearTimeout(autoRefreshTimer);
        setTimeout(() => location.reload(), 1200);
    } catch (err) {
        alert('Gagal mengubah status.');
    } finally {
        btn.style.opacity = '1'; btn.style.cursor = 'pointer'; isLoading = false;
    }
}
</script>

{{-- ── Cancel modal ───────────────────────────────────── --}}
<script>
function openCancel(orderId, orderCode) {
    // FIX BUG 5: Ambil base URL dari data-attribute, bukan string hardcoded
    const base = document.getElementById('cancelModal').dataset.cancelBase;
    document.getElementById('cancelForm').action = base + '/' + orderId + '/cancel';
    document.getElementById('cancelOrderCode').textContent = 'Pesanan ' + orderCode + ' akan dibatalkan.';
    document.getElementById('cancelReason').value = '';
    document.getElementById('cancelModal').classList.add('open');
}
function closeCancel() {
    document.getElementById('cancelModal').classList.remove('open');
}
function validateCancel() {
    if (!document.getElementById('cancelReason').value) {
        alert('Pilih alasan pembatalan.'); return false;
    }
    return true;
}
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancel();
});
</script>

{{-- ── Chat (multi-order support) ────────────────────── --}}
@if($activeOrders->isNotEmpty())
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
const CHAT_AUTH_ID   = {{ auth()->id() }};
const PUSHER_KEY     = '50f762dbbea60782d00e';
const PUSHER_CLUSTER = 'ap1';
const chatState      = {}; // { orderId: { open, unread, pusher, channel } }

function initChat(orderId) {
    if (chatState[orderId]) return;
    chatState[orderId] = { open: false, unread: 0 };

    const pusher  = new Pusher(PUSHER_KEY, { cluster: PUSHER_CLUSTER, forceTLS: true });
    const channel = pusher.subscribe('order-chat.' + orderId);
    channel.bind('new-message', function(data) {
        if (data.sender_id !== CHAT_AUTH_ID) {
            appendMsg(orderId, data, false);
            if (!chatState[orderId].open) {
                chatState[orderId].unread++;
                updateBadge(orderId);
            }
        }
    });
    chatState[orderId].pusher  = pusher;
    chatState[orderId].channel = channel;

    // Cek unread on load
    fetch('/chat/' + orderId + '/unread-count', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(d => {
        if (d.unread > 0) {
            chatState[orderId].unread = d.unread;
            updateBadge(orderId);
        }
    }).catch(() => {});
}

function toggleChat(orderId) {
    if (!chatState[orderId]) initChat(orderId);
    const isOpen = chatState[orderId].open;
    const box    = document.getElementById('chatBox_' + orderId);
    if (!isOpen) {
        box.style.display = 'flex';
        chatState[orderId].open   = true;
        chatState[orderId].unread = 0;
        updateBadge(orderId);
        loadMsgs(orderId);
        // FIX BUG 3: Mark as read di server saat chat dibuka
        fetch('/chat/' + orderId + '/mark-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).catch(() => {});
    } else {
        box.style.display = 'none';
        chatState[orderId].open = false;
    }
}

function loadMsgs(orderId) {
    fetch('/chat/' + orderId + '/messages', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(msgs => {
        const c = document.getElementById('chatMessages_' + orderId);
        c.innerHTML = '';
        if (!msgs.length) {
            c.innerHTML = '<div style="text-align:center;color:#94a3b8;font-size:11px;padding:16px 0;">Belum ada pesan 👋</div>';
            return;
        }
        msgs.forEach(m => appendMsg(orderId, m, m.is_mine));
        scrollBot(orderId);
    }).catch(() => {
        document.getElementById('chatMessages_' + orderId).innerHTML =
            '<div style="text-align:center;color:#ef4444;font-size:11px;padding:12px 0;">Gagal memuat.</div>';
    });
}

function appendMsg(orderId, data, isMine) {
    const c = document.getElementById('chatMessages_' + orderId);
    if (!c) return;
    const w = document.createElement('div');
    w.style.cssText = 'display:flex;flex-direction:column;align-items:' + (isMine ? 'flex-end' : 'flex-start');
    w.innerHTML = (isMine ? '' : `<div class="chat-name">${esc(data.sender_name)}</div>`) +
        `<div class="chat-bubble ${isMine?'mine':'theirs'}">${esc(data.message)}<div class="chat-time">${data.time}</div></div>`;
    c.appendChild(w);
    scrollBot(orderId);
}

function sendMessage(orderId) {
    const inp = document.getElementById('chatInput_' + orderId);
    const msg = inp.value.trim();
    if (!msg) return;
    inp.value = ''; inp.disabled = true;
    fetch('/chat/' + orderId + '/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ message: msg }),
    }).then(r => r.json()).then(d => {
        appendMsg(orderId, d, true);
        inp.disabled = false; inp.focus();
    }).catch(() => {
        inp.disabled = false; inp.value = msg;
        alert('Gagal mengirim pesan.');
    });
}

function scrollBot(orderId) {
    const el = document.getElementById('chatMessages_' + orderId);
    if (el) el.scrollTop = el.scrollHeight;
}

function updateBadge(orderId) {
    const b = document.getElementById('chatUnreadBadge_' + orderId);
    if (!b) return;
    const n = chatState[orderId]?.unread ?? 0;
    if (n > 0) { b.textContent = n > 9 ? '9+' : n; b.classList.add('show'); }
    else { b.classList.remove('show'); }
}

function esc(s) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(s));
    return d.innerHTML;
}

// Init chat untuk semua order aktif
document.addEventListener('DOMContentLoaded', function() {
    @foreach($activeOrders as $ao)
    @php
        $cSt = $ao->isRide()
            ? ['accepted','on_the_way','picked_up','arrived']
            : ['accepted','picking_up','in_progress','delivered'];
    @endphp
    @if(in_array($ao->status, $cSt))
    initChat({{ $ao->id }});
    @endif
    @endforeach
});
</script>
@endif
@endpush