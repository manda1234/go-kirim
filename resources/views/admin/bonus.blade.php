@extends('layouts.app')
@section('title','Manajemen Bonus Performa')

@section('sidebar-nav')
  <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
  <a href="{{ route('admin.transactions') }}" class="sidebar-link"><i class="fas fa-receipt w-5"></i> Transaksi</a>
  <a href="{{ route('admin.customers') }}" class="sidebar-link"><i class="fas fa-users w-5"></i> Customer</a>
  <a href="{{ route('admin.mitras') }}" class="sidebar-link"><i class="fas fa-motorcycle w-5"></i> Mitra</a>
  <a href="{{ route('admin.bonus.index') }}" class="sidebar-link "><i class="fas fa-trophy w-5"></i> Bonus Performa</a>
  <a href="{{ route('admin.rates') }}" class="sidebar-link"><i class="fas fa-cog w-5"></i> Setting Tarif</a>
  <a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="fas fa-qrcode w-5"></i> Setting QRIS</a>
@endsection

@section('bottom-nav')
  <a href="{{ route('admin.dashboard') }}" class="bnav-item"><i class="fas fa-home text-xl"></i><span>Dashboard</span></a>
  <a href="{{ route('admin.transactions') }}" class="bnav-item"><i class="fas fa-receipt text-xl"></i><span>Transaksi</span></a>
  <a href="{{ route('admin.mitras') }}" class="bnav-item"><i class="fas fa-motorcycle text-xl"></i><span>Mitra</span></a>
  <a href="{{ route('admin.bonus.index') }}" class="bnav-item active"><i class="fas fa-trophy text-xl"></i><span>Bonus</span></a>
@endsection

@section('content')
<div class="max-w-5xl mx-auto p-4 space-y-5">

  {{-- Header --}}
  <div class="flex items-center justify-between pt-2">
    <h1 class="font-black text-xl text-slate-800">🏆 Bonus Performa</h1>
    <form method="POST" action="{{ route('admin.bonus.process') }}"
          onsubmit="return confirm('Proses bonus untuk semua mitra periode ini?')">
      @csrf
      <input type="hidden" name="period" value="week">
      <button type="submit" class="btn-primary text-sm px-4 py-2">
        <i class="fas fa-play mr-1"></i> Proses Semua Bonus
      </button>
    </form>
  </div>

 
  {{-- ── Grid: kiri konfigurasi tier, kanan rekap mitra ── --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- ══ Konfigurasi Tier ══ --}}
    <div class="space-y-4">
      <div class="card p-5">
        <h2 class="font-black text-slate-800 text-base mb-1">⚙️ Konfigurasi Tier Bonus</h2>
        <p class="text-xs text-slate-400 mb-4">Nominal bonus yang diterima mitra per periode</p>

        <div class="space-y-3">
          @foreach($rates as $rate)
          <form method="POST" action="{{ route('admin.bonus.update', $rate->id) }}"
                class="border border-slate-100 rounded-xl p-4 hover:border-slate-200 transition-colors">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2">
                <span class="text-base">
                  {{ match($rate->tier_name) {
                      'platinum' => '💎',
                      'gold'     => '🥇',
                      'silver'   => '🥈',
                      default    => '🥉'
                  } }}
                </span>
                <div>
                  <p class="font-black text-slate-800 text-sm capitalize">{{ $rate->tier_name }}</p>
                  <p class="text-xs text-slate-400">{{ $rate->min_orders }}–{{ $rate->max_orders ?? '∞' }} order</p>
                </div>
              </div>
              <label class="flex items-center gap-2 cursor-pointer">
                <span class="text-xs text-slate-500">Aktif</span>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ $rate->is_active ? 'checked' : '' }}
                       class="w-4 h-4 accent-green-600">
              </label>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs text-slate-500 font-medium block mb-1">Bonus Tier (Rp)</label>
                <input type="number" name="bonus_amount" value="{{ $rate->bonus_amount }}"
                       class="form-input text-sm w-full" min="0" step="500">
              </div>
              <div>
                <label class="text-xs text-slate-500 font-medium block mb-1">Bonus Rating ≥ {{ $rate->rating_threshold }} (Rp)</label>
                <input type="number" name="rating_bonus" value="{{ $rate->rating_bonus }}"
                       class="form-input text-sm w-full" min="0" step="500">
              </div>
              <div>
                <label class="text-xs text-slate-500 font-medium block mb-1">Min Order</label>
                <input type="number" name="min_orders" value="{{ $rate->min_orders }}"
                       class="form-input text-sm w-full" min="0">
              </div>
              <div>
                <label class="text-xs text-slate-500 font-medium block mb-1">Max Order</label>
                <input type="number" name="max_orders" value="{{ $rate->max_orders ?? '' }}"
                       class="form-input text-sm w-full" min="0" placeholder="∞ tidak terbatas">
              </div>
            </div>

            <button type="submit"
                    class="mt-3 w-full py-2 rounded-xl text-xs font-bold bg-primary-600 text-white hover:bg-primary-700 transition-colors">
              Simpan {{ ucfirst($rate->tier_name) }}
            </button>
          </form>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ══ Kanan: Rekap & Bonus Manual ══ --}}
    <div class="space-y-4">

      {{-- Rekap mitra --}}
      <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-black text-slate-800 text-base">📊 Rekap Mitra</h2>
            <p class="text-xs text-slate-400 mt-0.5">Minggu ini · berdasarkan order selesai</p>
          </div>
          <span class="bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 rounded-full">
            {{ $mitraRecap->count() }} mitra
          </span>
        </div>

        <div class="space-y-2">
          @forelse($mitraRecap as $item)
          <div class="flex items-center gap-3 py-3 border-b border-slate-50 last:border-0">
            {{-- Avatar --}}
            <div class="w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center
                        text-xs font-black text-primary-700 flex-shrink-0">
              {{ strtoupper(substr($item->name, 0, 2)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
              <p class="font-bold text-slate-800 text-sm truncate">{{ $item->name }}</p>
              <p class="text-xs text-slate-400 mt-0.5">
                {{ $item->order_count }} order ·
                ⭐ {{ number_format($item->avg_rating, 1) }} ·
                <span class="capitalize font-medium
                  {{ match($item->tier) {
                      'platinum' => 'text-purple-600',
                      'gold'     => 'text-amber-600',
                      'silver'   => 'text-slate-600',
                      default    => 'text-gray-400'
                  } }}">
                  {{ $item->tier }}
                </span>
              </p>
            </div>

            {{-- Bonus --}}
            <div class="text-right flex-shrink-0">
              <p class="font-black text-sm {{ $item->total_bonus > 0 ? 'text-primary-600' : 'text-slate-400' }}">
                Rp {{ number_format($item->total_bonus, 0, ',', '.') }}
              </p>
              @if($item->total_bonus > 0)
              <span class="text-xs bg-green-50 text-green-700 font-bold px-2 py-0.5 rounded-full">Layak</span>
              @else
              <span class="text-xs bg-slate-50 text-slate-400 font-bold px-2 py-0.5 rounded-full">Belum</span>
              @endif
            </div>
          </div>
          @empty
          <div class="text-center py-8">
            <div class="text-3xl mb-2">📭</div>
            <p class="text-slate-400 text-sm">Belum ada data mitra minggu ini</p>
          </div>
          @endforelse
        </div>
      </div>

      {{-- Bonus Manual --}}
      <div class="card p-5">
        <h2 class="font-black text-slate-800 text-base mb-1">✍️ Bonus Manual</h2>
        <p class="text-xs text-slate-400 mb-4">Untuk event khusus atau kompensasi di luar sistem tier</p>

        <form method="POST" action="{{ route('admin.bonus.manual') }}" class="space-y-3">
          @csrf
          <div>
            <label class="text-xs text-slate-500 font-medium block mb-1">Pilih Mitra</label>
            <select name="mitra_id" class="form-select w-full text-sm" required>
              <option value="">-- Pilih mitra --</option>
              @foreach($mitras as $mitra)
              <option value="{{ $mitra->id }}">{{ $mitra->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-slate-500 font-medium block mb-1">Nominal (Rp)</label>
              <input type="number" name="amount" class="form-input text-sm w-full"
                     placeholder="10000" min="0" step="500" required>
            </div>
            <div>
              <label class="text-xs text-slate-500 font-medium block mb-1">Periode</label>
              <select name="period" class="form-select text-sm w-full">
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
                <option value="today">Hari Ini</option>
              </select>
            </div>
          </div>
          <div>
            <label class="text-xs text-slate-500 font-medium block mb-1">Keterangan</label>
            <input type="text" name="note" class="form-input text-sm w-full"
                   placeholder="Contoh: Bonus event lebaran" maxlength="100">
          </div>
          <button type="submit" class="w-full btn-primary text-sm py-2.5">
            <i class="fas fa-plus mr-1"></i> Tambahkan Bonus
          </button>
        </form>
      </div>

    </div>
  </div>

  {{-- ── Riwayat Bonus ── --}}
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
      <h2 class="font-black text-slate-800 text-base">📋 Riwayat Bonus Dicairkan</h2>
      <span class="text-xs text-slate-400">{{ $history->total() }} total transaksi</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Mitra</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase hidden sm:table-cell">Tier</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Bonus</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase hidden md:table-cell">Keterangan</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase hidden lg:table-cell">Tanggal</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tipe</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($history as $h)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-semibold text-slate-800">{{ $h->mitra?->name ?? '–' }}</td>
            <td class="px-5 py-3 hidden sm:table-cell">
              <span class="capitalize text-xs font-bold px-2 py-1 rounded-full
                {{ match($h->tier_name ?? 'bronze') {
                    'platinum' => 'bg-purple-100 text-purple-700',
                    'gold'     => 'bg-amber-100 text-amber-700',
                    'silver'   => 'bg-slate-100 text-slate-600',
                    default    => 'bg-gray-100 text-gray-500'
                } }}">
                {{ $h->tier_name ?? '–' }}
              </span>
            </td>
            <td class="px-5 py-3 font-black text-primary-600">
              +Rp {{ number_format($h->bonus, 0, ',', '.') }}
            </td>
            <td class="px-5 py-3 text-slate-400 text-xs hidden md:table-cell">
              {{ $h->note ?? 'Bonus sistem otomatis' }}
            </td>
            <td class="px-5 py-3 text-slate-400 text-xs hidden lg:table-cell">
              {{ $h->earned_date ? \Carbon\Carbon::parse($h->earned_date)->format('d M Y') : '–' }}
            </td>
            <td class="px-5 py-3">
              <span class="text-xs font-bold px-2 py-1 rounded-full
                {{ $h->type === 'manual'
                    ? 'bg-blue-50 text-blue-600'
                    : 'bg-green-50 text-green-600' }}">
                {{ $h->type === 'manual' ? 'Manual' : 'Otomatis' }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
              Belum ada riwayat bonus
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($history->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $history->links() }}</div>
    @endif
  </div>

</div>
@endsection