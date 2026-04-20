{{-- resources/views/admin/pending-transfers.blade.php --}}
@extends('layouts.app')
@section('title', 'Bukti Transfer Menunggu Konfirmasi')

@section('sidebar-nav')
  <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
  <a href="{{ route('admin.transactions') }}" class="sidebar-link"><i class="fas fa-receipt w-5"></i> Transaksi</a>
  <a href="{{ route('admin.customers') }}" class="sidebar-link"><i class="fas fa-users w-5"></i> Customer</a>
  <a href="{{ route('admin.mitras') }}" class="sidebar-link"><i class="fas fa-motorcycle w-5"></i> Mitra</a>
  <a href="{{ route('admin.bonus.index') }}" class="sidebar-link"><i class="fas fa-trophy w-5"></i> Bonus Performa</a>
  <a href="{{ route('admin.rates') }}" class="sidebar-link"><i class="fas fa-cog w-5"></i> Setting Tarif</a>
  <a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="fas fa-qrcode w-5"></i> Setting QRIS</a>
  <a href="{{ route('admin.payments.transfers') }}" class="sidebar-link active"><i class="fas fa-wallet w-5"></i> Pending Transfer</a>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="font-black text-xl text-slate-800">Konfirmasi Transfer</h1>
            <p class="text-sm text-slate-400">Bukti transfer yang menunggu verifikasi</p>
        </div>
        <span class="w-fit px-3 py-1 bg-amber-100 text-amber-700 font-bold text-sm rounded-full">
            {{ $orders->total() }} menunggu
        </span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-semibold flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Search --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari no. order atau nama customer..."
               class="flex-1 border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300">
        <button type="submit"
                class="w-full sm:w-auto px-4 py-2 bg-slate-700 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-search"></i>
        </button>
    </form>

    {{-- List --}}
    @forelse($orders as $order)
    <div class="bg-white border border-slate-200 rounded-2xl p-4 md:p-5 space-y-4">

        {{-- Info order --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div>
                <p class="font-black text-slate-800 text-sm md:text-base">{{ $order->order_code }}</p>
                <p class="text-xs md:text-sm text-slate-500">
                    {{ $order->customer->name }}
                    · Transfer Bank
                    · {{ $order->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="text-left md:text-right">
                <p class="font-black text-base md:text-lg text-green-600">
                    Rp {{ number_format($order->total_fare, 0, ',', '.') }}
                </p>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                    Menunggu Konfirmasi
                </span>
            </div>
        </div>

        {{-- Bukti transfer --}}
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Bukti Transfer</p>
            <a href="{{ $order->payment_proof_url }}" target="_blank">
                <img src="{{ $order->payment_proof_url }}"
                     class="w-full max-h-64 md:max-h-72 object-contain rounded-xl border border-slate-200">
            </a>
        </div>

        {{-- Aksi --}}
        <div class="flex flex-col sm:flex-row gap-2 pt-2 border-t border-slate-100">

            {{-- Konfirmasi --}}
            <form method="POST"
                  action="{{ route('admin.orders.confirm-payment', $order) }}"
                  class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl">
                    <i class="fas fa-check"></i> Konfirmasi
                </button>
            </form>

            {{-- Toggle reject --}}
            <button type="button"
                    onclick="document.getElementById('reject-form-{{ $order->id }}').classList.toggle('hidden')"
                    class="w-full sm:w-auto px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl border border-red-200">
                <i class="fas fa-times"></i> Tolak
            </button>

            {{-- Detail --}}
            <a href="{{ route('admin.orders.detail', $order) }}"
               class="w-full sm:w-auto px-4 py-2 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl text-center">
                <i class="fas fa-eye"></i> Detail
            </a>
        </div>

        {{-- Form tolak --}}
        <form id="reject-form-{{ $order->id }}"
              method="POST"
              action="{{ route('admin.orders.reject-payment', $order) }}"
              class="hidden space-y-2 pt-3 border-t border-red-100">
            @csrf
            <input type="text" name="reject_reason" required
                   placeholder="Alasan penolakan..."
                   class="w-full border border-red-300 rounded-xl px-4 py-2 text-sm">
            <button type="submit"
                    class="w-full px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-xl">
                Kirim Penolakan
            </button>
        </form>

    </div>
    @empty
    <div class="text-center py-20 text-slate-400">
        <i class="fas fa-check-circle text-5xl text-green-400 mb-4 block"></i>
        <p class="font-bold text-slate-500 text-lg">Tidak ada transfer</p>
    </div>
    @endforelse

    <div>{{ $orders->links() }}</div>

</div>
@endsection