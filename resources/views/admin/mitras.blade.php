@extends('layouts.app')
@section('title', 'Manajemen Mitra')
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
    <a href="{{ route('admin.dashboard') }}" class="bnav-item"><i class="fas fa-home text-xl"></i><span>Dashboard</span></a>
    <a href="{{ route('admin.transactions') }}" class="bnav-item"><i
            class="fas fa-receipt text-xl"></i><span>Transaksi</span></a>
    <a href="{{ route('admin.mitras') }}" class="bnav-item active"><i
            class="fas fa-users text-xl"></i><span>Mitra</span></a>
    <a href="{{ route('admin.rates') }}" class="bnav-item"><i class="fas fa-cog text-xl"></i><span>Setting</span></a>
@endsection
@section('content')
    <div class="max-w-4xl mx-auto p-4 space-y-4">
      
        <div class="flex items-center justify-between pt-2">
            <h1 class="font-black text-xl text-slate-800">🏍️ Manajemen Mitra</h1>
            <span class="badge badge-info">{{ $mitras->total() }} total</span>
        </div>

        <form method="GET" class="card p-4 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..."
                class="form-input flex-1">
            <select name="vehicle_type" class="form-select w-36">
                <option value="">Semua</option>
                <option value="motor" {{ request('vehicle_type') === 'motor' ? 'selected' : '' }}>🏍️ Motor</option>
                <option value="mobil" {{ request('vehicle_type') === 'mobil' ? 'selected' : '' }}>🚗 Mobil</option>
            </select>
            <button type="submit" class="btn-primary px-5 text-sm">Filter</button>
        </form>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Mitra</th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden sm:table-cell">
                                Kendaraan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Rating</th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden md:table-cell">
                                Total Trip</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mitras as $m)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $m->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $m->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $m->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 hidden sm:table-cell">
                                    {{ $m->mitraProfile?->vehicle_brand }} · {{ $m->mitraProfile?->vehicle_plate }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-amber-500">⭐ {{ $m->mitraProfile?->rating ?? '–' }}
                                </td>
                                <td class="px-5 py-3.5 font-bold hidden md:table-cell">{{ $m->orders_as_mitra_count }}</td>

                                {{-- STATUS --}}
                                <td class="px-5 py-3.5">
                                    @php $vStatus = $m->mitraProfile?->status ?? 'pending'; @endphp
                                    <div class="flex flex-col gap-1.5">

                                        {{-- Cek suspended dulu --}}
                                        @if (!$m->is_active)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-ban mr-1"></i> Disuspend
                                            </span>
                                        @elseif($vStatus === 'verified')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                            </span>
                                        @elseif($vStatus === 'rejected')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif

                                        {{-- Online/Offline — otomatis offline jika suspended --}}
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="w-2 h-2 rounded-full
                {{ $m->mitraProfile?->is_online && $m->is_active ? 'bg-green-500' : 'bg-slate-300' }}">
                                            </span>
                                            <span class="text-xs text-slate-400">
                                                {{ $m->mitraProfile?->is_online && $m->is_active ? 'Online' : 'Offline' }}
                                            </span>
                                        </div>

                                    </div>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-col gap-2">

                                        {{-- Tombol Suspend / Aktifkan --}}
                                        <form method="POST" action="{{ route('admin.mitras.toggle', $m->id) }}"
                                            onsubmit="return confirm('{{ $m->is_active
                                                ? 'Yakin ingin suspend mitra ' . $m->name . '? Mitra tidak bisa menerima order.'
                                                : 'Aktifkan kembali mitra ' . $m->name . '?' }}')">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs font-bold {{ $m->is_active ? 'text-red-500 hover:text-red-700' : 'text-emerald-600 hover:text-emerald-800' }}">
                                                {{ $m->is_active ? '🚫 Suspend' : '✅ Aktifkan' }}
                                            </button>
                                        </form>

                                        {{-- Tombol Verifikasi (hanya jika aktif) --}}
                                        @if ($m->is_active && in_array($vStatus, ['pending', 'rejected']))
                                            <form method="POST" action="{{ route('admin.mitras.verify', $m->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit"
                                                    class="text-xs font-bold text-emerald-600 hover:text-emerald-800"
                                                    onclick="return confirm('Verifikasi mitra {{ $m->name }}?')">
                                                    <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Tolak (hanya jika aktif) --}}
                                        @if ($m->is_active && in_array($vStatus, ['verified', 'pending']))
                                            <form method="POST" action="{{ route('admin.mitras.verify', $m->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                    class="text-xs font-bold text-red-400 hover:text-red-600"
                                                    onclick="return confirm('Tolak verifikasi mitra {{ $m->name }}?')">
                                                    <i class="fas fa-times-circle mr-1"></i> Tolak
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">Tidak ada mitra ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">{{ $mitras->links() }}</div>
        </div>
    </div>
@endsection
