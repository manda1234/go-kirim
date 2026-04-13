@extends('layouts.app')
@section('title','Pendapatan Saya')

@section('sidebar-nav')
  <a href="{{ route('mitra.dashboard') }}" class="sidebar-link">
    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
  </a>
  <a href="{{ route('mitra.earnings') }}" class="sidebar-link active">
    <i class="fas fa-wallet w-5"></i> Pendapatan
  </a>
  <a href="{{ route('mitra.history') }}" class="sidebar-link">
    <i class="fas fa-history w-5"></i> Riwayat Pesanan
  </a>
  <a href="{{ route('mitra.ratings') }}" class="sidebar-link">
    <i class="fas fa-star w-5"></i> Rating & Ulasan
  </a>
@endsection

@section('bottom-nav')
  <a href="{{ route('mitra.dashboard') }}" class="bnav-item">
    <i class="fas fa-home text-xl"></i><span>Dashboard</span>
  </a>
  <a href="{{ route('mitra.earnings') }}" class="bnav-item active">
    <i class="fas fa-wallet text-xl"></i><span>Pendapatan</span>
  </a>
  <a href="{{ route('mitra.history') }}" class="bnav-item">
    <i class="fas fa-history text-xl"></i><span>Riwayat</span>
  </a>
  <a href="{{ route('mitra.profile') }}" class="bnav-item">
    <i class="fas fa-user text-xl"></i><span>Profil</span>
  </a>
@endsection

@section('content')
{{-- ── Outer container ── --}}
<div class="w-full max-w-5xl mx-auto px-0 sm:px-2 md:px-4 space-y-4 sm:space-y-5 pb-8">

  {{-- Page title --}}
  <h1 class="font-black text-lg sm:text-xl md:text-2xl text-slate-800 pt-2 px-1">
    💰 Pendapatan Saya
  </h1>

  {{-- ── Period tabs ── --}}
  <div class="flex gap-2 flex-wrap px-1">
    @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $val => $label)
    <a href="?period={{ $val }}"
       class="px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all
              {{ $period === $val
                  ? 'bg-primary-600 text-white shadow-sm'
                  : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50' }}">
      {{ $label }}
    </a>
    @endforeach
  </div>

  {{-- ── Two-column layout on lg+ ── --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">

    {{-- ══ LEFT / MAIN COLUMN (2/3) ═══════════════════════════════ --}}
    <div class="lg:col-span-2 space-y-4 sm:space-y-5">

      {{-- Summary card --}}
      <div class="card overflow-hidden">
        <div class="p-5 sm:p-6 md:p-8 bg-gradient-to-br from-primary-600 to-primary-500 text-white">
          <p class="text-green-100 text-xs sm:text-sm font-medium">Total Pendapatan Bersih</p>
          <p class="font-black mt-1 leading-none"
             style="font-size: clamp(1.9rem, 5vw, 3rem)">
            Rp {{ number_format($totalNet, 0, ',', '.') }}
          </p>
          <p class="text-green-200 text-xs sm:text-sm mt-2">
            {{ $totalOrders }} pesanan selesai
          </p>

          {{-- Mini stats --}}
          <div class="grid grid-cols-2 gap-2 sm:gap-3 mt-4 sm:mt-5">
            <div class="bg-white/15 rounded-xl p-3 sm:p-4 text-center">
              <p class="text-xs text-green-100 leading-tight">Rata-rata<br class="sm:hidden"> per Order</p>
              <p class="font-black text-base sm:text-lg mt-1">
                Rp {{ $totalOrders > 0 ? number_format($totalNet / $totalOrders, 0, ',', '.') : '–' }}
              </p>
            </div>
            <div class="bg-white/15 rounded-xl p-3 sm:p-4 text-center">
              <p class="text-xs text-green-100 leading-tight">Bonus<br class="sm:hidden"> Performa</p>
              <p class="font-black text-base sm:text-lg mt-1">
                Rp {{ number_format($list->sum('bonus'), 0, ',', '.') }}
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- Earnings list --}}
      <div class="space-y-2 sm:space-y-3">
        @forelse($list as $earn)
        <div class="card p-3 sm:p-4 flex items-center gap-3 sm:gap-4
                    hover:shadow-md transition-shadow">

          {{-- FIX: justify-content → justify-center --}}
          <div class="w-11 h-11 sm:w-12 sm:h-12 bg-primary-50 rounded-xl flex items-center
                      justify-center text-lg sm:text-xl flex-shrink-0">
            {{ $earn->order?->service_type === 'food_delivery' ? '🍜'
               : ($earn->order?->service_type === 'antar_orang' ? '🛵' : '📦') }}
          </div>

          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <p class="font-bold text-xs sm:text-sm text-slate-800">
              {{ $earn->order?->order_code ?? '–' }}
            </p>
            <p class="text-xs text-slate-400 truncate mt-0.5">
              {{ $earn->order?->pickup_address ?? '' }}
              @if($earn->order?->destination_address)
                → {{ $earn->order->destination_address }}
              @endif
            </p>
            <p class="text-xs text-slate-400 mt-0.5">
              {{-- FIX BUG 2: Pastikan earned_date tidak null sebelum format --}}
              {{ $earn->earned_date ? \Carbon\Carbon::parse($earn->earned_date)->format('d M Y') : '–' }}
            </p>
          </div>

          {{-- Amounts --}}
          <div class="text-right flex-shrink-0">
            <p class="font-black text-xs sm:text-sm text-primary-600">
              + Rp {{ number_format($earn->net_amount ?? 0, 0, ',', '.') }}
            </p>
            @if(($earn->bonus ?? 0) > 0)
            <p class="text-xs text-amber-500 font-bold mt-0.5">
              +bonus Rp {{ number_format($earn->bonus, 0, ',', '.') }}
            </p>
            @endif
            <p class="text-xs text-slate-400 mt-0.5">
              -Rp {{ number_format($earn->platform_fee ?? 0, 0, ',', '.') }}
            </p>
          </div>

        </div>
        @empty
        <div class="card p-10 sm:p-14 text-center">
          <div class="text-4xl sm:text-5xl mb-3">💸</div>
          <p class="font-bold text-slate-600 sm:text-lg">Belum ada pendapatan</p>
          <p class="text-slate-400 text-xs sm:text-sm mt-1">
            Selesaikan pesanan untuk mulai mendapatkan pendapatan
          </p>
        </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($list instanceof \Illuminate\Pagination\LengthAwarePaginator)
      <div class="px-1">{{ $list->appends(['period' => $period])->links() }}</div>
      @endif

    </div>{{-- end main column --}}

    {{-- ══ RIGHT SIDEBAR (1/3 on lg) ═══════════════════════════════ --}}
    <div class="space-y-4">

      {{-- Quick stats breakdown --}}
      <div class="card p-4 sm:p-5">
        <h2 class="font-black text-slate-800 text-sm sm:text-base mb-4">📊 Ringkasan</h2>

        <div class="space-y-3">
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-xs sm:text-sm text-slate-500 font-medium">Total Kotor</span>
            <span class="font-black text-xs sm:text-sm text-slate-700">
              {{-- FIX BUG 3: gross_amount sekarang ada di fallback object --}}
              Rp {{ number_format($list->sum('gross_amount'), 0, ',', '.') }}
            </span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-xs sm:text-sm text-slate-500 font-medium">Potongan Platform</span>
            <span class="font-black text-xs sm:text-sm text-red-500">
              -Rp {{ number_format($list->sum('platform_fee'), 0, ',', '.') }}
            </span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-xs sm:text-sm text-slate-500 font-medium">Bonus Performa</span>
            <span class="font-black text-xs sm:text-sm text-amber-500">
              +Rp {{ number_format($list->sum('bonus'), 0, ',', '.') }}
            </span>
          </div>
          <div class="flex justify-between items-center pt-1">
            <span class="text-sm font-black text-slate-800">Pendapatan Bersih</span>
            <span class="font-black text-sm text-primary-600">
              Rp {{ number_format($totalNet, 0, ',', '.') }}
            </span>
          </div>
        </div>
      </div>

      {{-- ── Bonus Performa Card ── --}}
<div class="card p-4 sm:p-5">
  <h2 class="font-black text-slate-800 text-sm sm:text-base mb-4">🏆 Bonus Performa</h2>

  {{-- Tier saat ini --}}
  <div class="flex items-center justify-between mb-2">
    <span class="text-xs text-slate-500">Tier kamu</span>
    <span class="px-2 py-1 rounded-full text-xs font-bold
      {{ match($bonus['tier']) {
          'platinum' => 'bg-purple-100 text-purple-700',
          'gold'     => 'bg-amber-100 text-amber-700',
          'silver'   => 'bg-slate-100 text-slate-600',
          default    => 'bg-gray-100 text-gray-500'
      } }}">
      {{ ucfirst($bonus['tier']) }}
    </span>
  </div>

  {{-- Progress bar ke tier berikutnya --}}
  @if($bonus['next_tier'])
  <div class="mb-3">
    @php
      $current   = $bonus['order_count'];
      $nextMin   = $bonus['next_tier']->min_orders;
      $prevMin   = max(1, $nextMin - ($bonus['next_tier']->min_orders - ($current < $nextMin ? $current : $nextMin)));
      $pct       = min(100, round($current / $nextMin * 100));
    @endphp
    <div class="flex justify-between text-xs text-slate-400 mb-1">
      <span>{{ $current }} order</span>
      <span>Target {{ $nextMin }} order</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-2">
      <div class="bg-primary-500 h-2 rounded-full transition-all"
           style="width: {{ $pct }}%"></div>
    </div>
    <p class="text-xs text-slate-400 mt-1">
      {{ $nextMin - $current }} pesanan lagi →
      <span class="text-amber-600 font-bold">
        +Rp {{ number_format($bonus['next_tier']->bonus_amount - $bonus['tier_bonus'], 0, ',', '.') }}
      </span>
    </p>
  </div>
  @endif

  {{-- Breakdown bonus --}}
  <div class="space-y-2 text-xs">
    <div class="flex justify-between">
      <span class="text-slate-500">Bonus tier</span>
      <span class="font-bold text-primary-600">
        Rp {{ number_format($bonus['tier_bonus'], 0, ',', '.') }}
      </span>
    </div>
    <div class="flex justify-between">
      <span class="text-slate-500">
        Bonus rating
        @if($bonus['avg_rating'] < 4.8)
          <span class="text-slate-400">(rating kamu {{ $bonus['avg_rating'] }})</span>
        @endif
      </span>
      <span class="font-bold {{ $bonus['rating_bonus'] > 0 ? 'text-amber-500' : 'text-slate-400' }}">
        {{ $bonus['rating_bonus'] > 0 ? '+' : '' }}Rp {{ number_format($bonus['rating_bonus'], 0, ',', '.') }}
      </span>
    </div>
    <div class="flex justify-between border-t border-slate-100 pt-2">
      <span class="font-black text-slate-800">Total Bonus</span>
      <span class="font-black text-primary-600">
        Rp {{ number_format($bonus['total_bonus'], 0, ',', '.') }}
      </span>
    </div>
  </div>

  @if($bonus['avg_rating'] < 4.8)
  <div class="mt-3 bg-amber-50 border border-amber-100 rounded-xl p-2.5 text-xs text-amber-700">
    ⭐ Tingkatkan rating ke 4.8 untuk bonus tambahan
    <span class="font-bold">+Rp 5.000</span>
  </div>
  @endif
</div>

      {{-- Period switcher --}}
      <div class="card p-4 sm:p-5">
        <h2 class="font-black text-slate-800 text-sm sm:text-base mb-3">📅 Ganti Periode</h2>
        <div class="flex flex-col gap-2">
          @foreach(['today' => '📆 Hari Ini', 'week' => '🗓️ Minggu Ini', 'month' => '📅 Bulan Ini'] as $val => $label)
          <a href="?period={{ $val }}"
             class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs sm:text-sm
                    font-semibold transition-all
                    {{ $period === $val
                        ? 'bg-primary-50 text-primary-700 border border-primary-200'
                        : 'text-slate-500 hover:bg-slate-50 border border-transparent' }}">
            {{ $label }}
            @if($period === $val)
            <i class="fas fa-check text-primary-500 text-xs"></i>
            @else
            <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
            @endif
          </a>
          @endforeach
        </div>
      </div>

      {{-- Back to dashboard --}}
      <a href="{{ route('mitra.dashboard') }}"
         class="card p-4 flex items-center gap-3 hover:shadow-md transition-all group">
        {{-- FIX: justify-content → justify-center --}}
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    text-xl bg-blue-50">🏠</div>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-slate-800 text-sm">Kembali ke Dashboard</p>
          <p class="text-xs text-slate-400 mt-0.5">Lihat pesanan aktif</p>
        </div>
        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-slate-500
                  transition flex-shrink-0"></i>
      </a>

    </div>{{-- end sidebar --}}

  </div>{{-- end grid --}}

</div>
@endsection