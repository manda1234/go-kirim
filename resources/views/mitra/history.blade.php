@extends('layouts.app')
@section('title', 'Riwayat Pesanan')

{{-- ── SIDEBAR NAV (sama struktur dengan dashboard) ──────────────────── --}}
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

{{-- ── BOTTOM NAV ──────────────────────────────────────────────────────── --}}
@section('bottom-nav')
  <a href="{{ route('mitra.dashboard') }}" class="bnav-item">
    <i class="fas fa-home text-xl"></i><span>Dashboard</span>
  </a>
  <a href="{{ route('mitra.earnings') }}" class="bnav-item">
    <i class="fas fa-wallet text-xl"></i><span>Pendapatan</span>
  </a>
  <a href="{{ route('mitra.history') }}" class="bnav-item active">
    <i class="fas fa-history text-xl"></i><span>Riwayat</span>
  </a>
  <a href="{{ route('mitra.profile') }}" class="bnav-item active"><i class="fas fa-user text-xl"></i><span>Profil</span></a>
@endsection

@push('styles')
<style>
/* ── History page styles ─────────────────────────────────── */
.history-stat-card {
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
    border: 1.5px solid #f1f5f9;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    transition: box-shadow .2s;
}
.history-stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.07); }
.history-stat-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}

/* ── Filter tabs ─────────────────────────────────────────── */
.filter-tab {
    padding: 6px 16px; border-radius: 20px;
    font-size: 12px; font-weight: 700;
    border: 1.5px solid #e2e8f0;
    background: white; color: #64748b;
    cursor: pointer; transition: all .18s;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
}
.filter-tab:hover { border-color: #16a34a; color: #16a34a; }
.filter-tab.active {
    background: #16a34a; color: white;
    border-color: #16a34a;
}

/* ── Search bar ──────────────────────────────────────────── */
.search-wrap {
    position: relative; flex: 1; min-width: 0;
}
.search-wrap input {
    width: 100%; padding: 9px 12px 9px 36px;
    border: 1.5px solid #e2e8f0; border-radius: 12px;
    font-size: 13px; outline: none;
    transition: border-color .18s;
    background: white;
}
.search-wrap input:focus { border-color: #16a34a; }
.search-wrap .search-icon {
    position: absolute; left: 11px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8; font-size: 13px;
    pointer-events: none;
}

/* ── Order history card ──────────────────────────────────── */
.history-card {
    background: white;
    border: 1.5px solid #f1f5f9;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    transition: box-shadow .18s, border-color .18s;
    position: relative;
    overflow: hidden;
}
.history-card:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,.07);
    border-color: #e2e8f0;
}
.history-card::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; border-radius: 3px 0 0 3px;
}
.history-card.completed::before { background: #16a34a; }
.history-card.cancelled::before { background: #ef4444; }
.history-card.rejected::before  { background: #f97316; }

.svc-icon-wrap {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}

/* ── Badge ───────────────────────────────────────────────── */
.hbadge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 800;
    padding: 3px 8px; border-radius: 8px;
    white-space: nowrap; flex-shrink: 0;
}
.hbadge.completed { background: #dcfce7; color: #15803d; }
.hbadge.cancelled { background: #fee2e2; color: #dc2626; }
.hbadge.rejected  { background: #ffedd5; color: #c2410c; }

/* ── Expand detail ───────────────────────────────────────── */
.card-detail {
    display: none;
    margin-top: 10px; padding-top: 10px;
    border-top: 1px dashed #e2e8f0;
}
.card-detail.open { display: block; }

/* ── Empty state ─────────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 56px 16px;
    background: white; border-radius: 18px;
    border: 1.5px dashed #e2e8f0;
}

/* ── Pagination ──────────────────────────────────────────── */
.page-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: 10px;
    font-size: 12px; font-weight: 700; border: 1.5px solid #e2e8f0;
    color: #64748b; background: white; text-decoration: none;
    transition: all .15s;
}
.page-btn:hover { border-color: #16a34a; color: #16a34a; }
.page-btn.active { background: #16a34a; color: white; border-color: #16a34a; }
.page-btn.disabled { opacity: .4; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="w-full max-w-5xl mx-auto px-0 sm:px-2 md:px-4 space-y-4 sm:space-y-5 pb-10">

  {{-- ── HEADER BANNER ───────────────────────────────────────────────── --}}
  <div class="relative overflow-hidden text-white
              rounded-none sm:rounded-2xl md:rounded-3xl
              p-4 sm:p-6 md:p-8"
       style="background: linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#334155 100%)">
    <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full pointer-events-none"
         style="background:rgba(255,255,255,.05)"></div>
    <div class="absolute -bottom-8 -left-8 w-28 h-28 rounded-full pointer-events-none"
         style="background:rgba(255,255,255,.03)"></div>
    <div class="relative">
      <div class="flex items-center gap-3 mb-1">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg"
             style="background:rgba(255,255,255,.1)">📋</div>
        <div>
          <h1 class="font-black text-lg sm:text-xl md:text-2xl leading-tight">Riwayat Pesanan</h1>
          <p class="text-slate-400 text-xs sm:text-sm">Semua pesanan yang sudah selesai atau dibatalkan</p>
        </div>
      </div>
    </div>
  </div>

  {{-- ── STATISTIK RINGKAS ────────────────────────────────────────────── --}}
  <div class="grid grid-cols-3 gap-2 sm:gap-3 px-0 sm:px-1">

    {{-- Total Order --}}
    <div class="history-stat-card">
      <div class="history-stat-icon" style="background:#eff6ff">📊</div>
      <div class="min-w-0">
        <p class="font-black text-slate-800 text-base sm:text-lg leading-none">{{ $stats['total_order'] }}</p>
        <p class="text-xs text-slate-400 mt-0.5 leading-tight">Total<br class="sm:hidden"> Order</p>
      </div>
    </div>

    {{-- Total Pendapatan --}}
    <div class="history-stat-card">
      <div class="history-stat-icon" style="background:#f0fdf4">💰</div>
      <div class="min-w-0">
        <p class="font-black leading-none text-sm sm:text-base" style="color:#16a34a">
          Rp {{ number_format($stats['total_earning'] / 1000, 0, ',', '.') }}K
        </p>
        <p class="text-xs text-slate-400 mt-0.5 leading-tight">Total<br class="sm:hidden"> Pendapatan</p>
      </div>
    </div>

    {{-- Total Cancel --}}
    <div class="history-stat-card">
      <div class="history-stat-icon" style="background:#fef2f2">❌</div>
      <div class="min-w-0">
        <p class="font-black text-slate-800 text-base sm:text-lg leading-none">{{ $stats['total_cancel'] }}</p>
        <p class="text-xs text-slate-400 mt-0.5 leading-tight">Total<br class="sm:hidden"> Cancel</p>
      </div>
    </div>

  </div>

  {{-- ── FILTER + SEARCH ─────────────────────────────────────────────── --}}
  <div class="px-0 sm:px-1">
    <form method="GET" action="{{ route('mitra.history') }}" id="filterForm">

      {{-- Search bar --}}
      <div class="flex gap-2 mb-3">
        <div class="search-wrap">
          <i class="fas fa-search search-icon"></i>
          <input type="text" name="search"
                 value="{{ request('search') }}"
                 placeholder="Cari kode pesanan…"
                 autocomplete="off"
                 onchange="document.getElementById('filterForm').submit()">
        </div>
        @if(request('search') || request('status'))
        <a href="{{ route('mitra.history') }}"
           class="px-3 h-10 flex items-center justify-center rounded-xl border-2 text-xs font-bold
                  border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-500 transition
                  flex-shrink-0 gap-1.5 whitespace-nowrap">
          <i class="fas fa-times text-xs"></i> Reset
        </a>
        @endif
      </div>

      {{-- Status filter tabs --}}
      <div class="flex gap-2 overflow-x-auto pb-1" style="scrollbar-width:none">
        <input type="hidden" name="status" id="statusInput" value="{{ request('status', '') }}">

        <button type="button"
                class="filter-tab {{ !request('status') ? 'active' : '' }}"
                onclick="setFilter('')">
          Semua
          <span class="text-xs opacity-75">({{ $stats['total_order'] }})</span>
        </button>

        <button type="button"
                class="filter-tab {{ request('status') === 'completed' ? 'active' : '' }}"
                onclick="setFilter('completed')">
          ✅ Selesai
          <span class="text-xs opacity-75">({{ $stats['total_order'] - $stats['total_cancel'] }})</span>
        </button>

        <button type="button"
                class="filter-tab {{ request('status') === 'cancelled' ? 'active' : '' }}"
                onclick="setFilter('cancelled')">
          ❌ Dibatalkan
          <span class="text-xs opacity-75">({{ $stats['total_cancel'] }})</span>
        </button>
      </div>

    </form>
  </div>

  {{-- ── ORDER LIST ───────────────────────────────────────────────────── --}}
  <div class="px-0 sm:px-1 space-y-2 sm:space-y-3">

    @forelse($orders as $order)
    @php
      $svcIcon = match($order->service_type) {
          'kirim_barang' => '📦',
          'antar_orang'  => '🛵',
          'antar_makanan', 'food' => '🍜',
          'sewa_mobil'   => '🚗',
          default        => '📋',
      };
      $svcBg = match($order->service_type) {
          'kirim_barang' => '#fff7ed',
          'antar_orang'  => '#f0fdf4',
          'antar_makanan', 'food' => '#fef9c3',
          'sewa_mobil'   => '#eff6ff',
          default        => '#f8fafc',
      };
      $statusClass = in_array($order->status, ['cancelled','rejected']) ? $order->status : 'completed';
      $statusLabel = match($order->status) {
          'completed' => 'Selesai',
          'cancelled' => 'Dibatalkan',
          'rejected'  => 'Ditolak',
          default     => ucfirst($order->status),
      };
    @endphp

    <div class="history-card {{ $statusClass }}" id="card_{{ $order->id }}">

      {{-- Service icon --}}
      <div class="svc-icon-wrap flex-shrink-0" style="background:{{ $svcBg }}">
        {{ $svcIcon }}
      </div>

      {{-- Main info --}}
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2 mb-1 flex-wrap">
          <div class="min-w-0">
            <p class="font-black text-slate-800 text-xs sm:text-sm leading-tight">
              {{ $order->order_code }}
            </p>
            <p class="text-xs text-slate-400 mt-0.5">
              {{ $order->service_label ?? ucwords(str_replace('_',' ',$order->service_type)) }}
              @if($order->distance_km) · {{ $order->distance_km }} km @endif
            </p>
          </div>
          <div class="flex flex-col items-end gap-1 flex-shrink-0">
            <span class="hbadge {{ $statusClass }}">
              {{ $statusLabel }}
            </span>
            <span class="font-black text-xs sm:text-sm {{ $statusClass === 'completed' ? 'text-green-600' : 'text-red-400' }}">
              @if($statusClass === 'completed')
                +Rp {{ number_format($order->mitra_earning ?? 0, 0, ',', '.') }}
              @else
                Rp 0
              @endif
            </span>
          </div>
        </div>

        {{-- Route preview --}}
        <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-2">
          <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#16a34a"></span>
          <span class="truncate">{{ Str::limit($order->pickup_address, 35) }}</span>
        </div>
        <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-2">
          <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#ef4444"></span>
          <span class="truncate">{{ Str::limit($order->destination_address, 35) }}</span>
        </div>

        {{-- Footer row --}}
        <div class="flex items-center justify-between flex-wrap gap-2">
          <p class="text-xs text-slate-400">
            <i class="far fa-clock mr-1"></i>
            {{ $order->created_at->format('d M Y, H:i') }}
          </p>
          <button onclick="toggleDetail({{ $order->id }})"
                  class="text-xs font-bold flex items-center gap-1 transition"
                  style="color:#16a34a" id="detailToggle_{{ $order->id }}">
            <span id="detailLabel_{{ $order->id }}">Detail</span>
            <i class="fas fa-chevron-down text-xs" id="detailChevron_{{ $order->id }}"
               style="transition:transform .2s"></i>
          </button>
        </div>

        {{-- Expandable detail --}}
        <div class="card-detail" id="detail_{{ $order->id }}">
          <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">

            @if($order->customer)
            <div>
              <p class="text-slate-400 font-semibold">Customer</p>
              <p class="text-slate-700 font-bold">{{ $order->customer->name }}</p>
            </div>
            @endif

            @if($order->item_weight)
            <div>
              <p class="text-slate-400 font-semibold">Berat Barang</p>
              <p class="text-slate-700 font-bold">{{ $order->item_weight }} kg</p>
            </div>
            @endif

            <div>
              <p class="text-slate-400 font-semibold">Total Fare</p>
              <p class="text-slate-700 font-bold">Rp {{ number_format($order->total_fare ?? 0, 0, ',', '.') }}</p>
            </div>

            <div>
              <p class="text-slate-400 font-semibold">Pendapatan Mitra</p>
              <p class="font-black" style="color:#16a34a">
                Rp {{ number_format($order->mitra_earning ?? 0, 0, ',', '.') }}
              </p>
            </div>

            @if($order->payment_method)
            <div>
              <p class="text-slate-400 font-semibold">Pembayaran</p>
              <p class="text-slate-700 font-bold capitalize">{{ $order->payment_method }}</p>
            </div>
            @endif

            @if($order->cancel_reason)
            <div class="col-span-2">
              <p class="text-slate-400 font-semibold">Alasan Batal</p>
              <p class="font-bold" style="color:#dc2626">{{ $order->cancel_reason }}</p>
            </div>
            @endif

          </div>
        </div>

      </div>
    </div>
    @empty

    {{-- Empty state --}}
    <div class="empty-state">
      <div class="text-5xl mb-3">📭</div>
      <p class="font-black text-slate-600 text-base">Belum ada riwayat pesanan</p>
      <p class="text-slate-400 text-sm mt-1">
        @if(request('search'))
          Tidak ada pesanan dengan kode "<strong>{{ request('search') }}</strong>"
        @elseif(request('status') === 'completed')
          Belum ada pesanan yang selesai
        @elseif(request('status') === 'cancelled')
          Belum ada pesanan yang dibatalkan
        @else
          Riwayat pesanan Anda akan muncul di sini
        @endif
      </p>
      @if(request('search') || request('status'))
      <a href="{{ route('mitra.history') }}"
         class="inline-block mt-4 px-5 py-2 rounded-xl text-sm font-bold text-white"
         style="background:#16a34a">
        Lihat Semua Pesanan
      </a>
      @endif
    </div>

    @endforelse
  </div>

  {{-- ── PAGINATION ───────────────────────────────────────────────────── --}}
  @if($orders->hasPages())
  <div class="px-0 sm:px-1">
    <div class="flex items-center justify-between flex-wrap gap-3">

      <p class="text-xs text-slate-400 font-semibold">
        Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
        dari {{ $orders->total() }} pesanan
      </p>

      <div class="flex items-center gap-1.5 flex-wrap">

        {{-- Prev --}}
        @if($orders->onFirstPage())
          <span class="page-btn disabled"><i class="fas fa-chevron-left text-xs"></i></span>
        @else
          <a href="{{ $orders->previousPageUrl() }}" class="page-btn">
            <i class="fas fa-chevron-left text-xs"></i>
          </a>
        @endif

        {{-- Page numbers --}}
        @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
          @if($page == $orders->currentPage())
            <span class="page-btn active">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
          @endif
        @endforeach

        {{-- Next --}}
        @if($orders->hasMorePages())
          <a href="{{ $orders->nextPageUrl() }}" class="page-btn">
            <i class="fas fa-chevron-right text-xs"></i>
          </a>
        @else
          <span class="page-btn disabled"><i class="fas fa-chevron-right text-xs"></i></span>
        @endif

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
// ── Filter tab handler ───────────────────────────────────────
function setFilter(status) {
    document.getElementById('statusInput').value = status;
    document.getElementById('filterForm').submit();
}

// ── Expand / collapse detail ─────────────────────────────────
function toggleDetail(id) {
    const detail  = document.getElementById('detail_' + id);
    const chevron = document.getElementById('detailChevron_' + id);
    const label   = document.getElementById('detailLabel_' + id);
    const isOpen  = detail.classList.toggle('open');
    chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    label.textContent = isOpen ? 'Tutup' : 'Detail';
}

// ── Auto-submit search on clear ──────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let debounce;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounce);
            if (this.value === '') {
                debounce = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 400);
            }
        });
    }
});
</script>
@endpush