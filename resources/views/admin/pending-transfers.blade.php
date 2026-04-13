{{-- resources/views/admin/pending-transfers.blade.php --}}
@extends('layouts.admin')
@section('title', 'Bukti Transfer Menunggu Konfirmasi')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="font-black text-xl text-slate-800">Konfirmasi Transfer</h1>
            <p class="text-sm text-slate-400">Bukti transfer yang menunggu verifikasi</p>
        </div>
        <span class="px-3 py-1 bg-amber-100 text-amber-700 font-bold text-sm rounded-full">
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
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari no. order atau nama customer..."
               class="flex-1 border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300">
        <button type="submit"
                class="px-4 py-2 bg-slate-700 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition">
            <i class="fas fa-search"></i>
        </button>
    </form>

    {{-- List --}}
    @forelse($orders as $order)
    <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">

        {{-- Info order --}}
        <div class="flex items-start justify-between gap-3 flex-wrap">
            <div>
                <p class="font-black text-slate-800">{{ $order->order_code }}</p>
                <p class="text-sm text-slate-500">
                    {{ $order->customer->name }}
                    · Transfer Bank
                    · {{ $order->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="text-right">
                <p class="font-black text-lg text-green-600">
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
            <a href="{{ $order->payment_proof_url }}" target="_blank" title="Klik untuk buka di tab baru">
                <img src="{{ $order->payment_proof_url }}"
                     class="max-h-72 rounded-xl border border-slate-200 object-contain cursor-zoom-in w-full"
                     alt="Bukti Transfer {{ $order->order_code }}">
            </a>
            <p class="text-xs text-slate-400 mt-1">
                <i class="fas fa-external-link-alt mr-1"></i> Klik gambar untuk buka ukuran penuh
            </p>
        </div>

        {{-- Aksi --}}
        <div class="flex gap-3 flex-wrap pt-2 border-t border-slate-100">

            {{-- Konfirmasi lunas --}}
            <form method="POST"
                  action="{{ route('admin.orders.confirm-payment', $order) }}"
                  onsubmit="return confirm('Konfirmasi pembayaran {{ $order->order_code }} sebagai LUNAS?')">
                @csrf
                <button type="submit"
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-check"></i> Konfirmasi Lunas
                </button>
            </form>

            {{-- Tombol tampilkan form tolak --}}
            <button type="button"
                    onclick="document.getElementById('reject-form-{{ $order->id }}').classList.toggle('hidden')"
                    class="px-5 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl border border-red-200 transition flex items-center gap-2">
                <i class="fas fa-times"></i> Tolak Bukti
            </button>

            {{-- Link ke detail order --}}
            <a href="{{ route('admin.orders.detail', $order) }}"
               class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition flex items-center gap-2">
                <i class="fas fa-eye"></i> Detail Order
            </a>
        </div>

        {{-- Form tolak (tersembunyi) --}}
        <form id="reject-form-{{ $order->id }}"
              method="POST"
              action="{{ route('admin.orders.reject-payment', $order) }}"
              class="hidden space-y-2 pt-3 border-t border-red-100">
            @csrf
            <p class="text-xs font-bold text-red-600">Alasan penolakan (akan dilihat customer):</p>
            <input type="text" name="reject_reason" required
                   placeholder="Contoh: Bukti tidak jelas, nominal tidak sesuai..."
                   class="w-full border border-red-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">
                    Kirim Penolakan
                </button>
                <button type="button"
                        onclick="document.getElementById('reject-form-{{ $order->id }}').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition">
                    Batal
                </button>
            </div>
        </form>

    </div>
    @empty
    <div class="text-center py-20 text-slate-400">
        <i class="fas fa-check-circle text-5xl text-green-400 mb-4 block"></i>
        <p class="font-bold text-slate-500 text-lg">Tidak ada transfer yang menunggu</p>
        <p class="text-sm mt-1">Semua bukti transfer sudah diverifikasi.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    <div>{{ $orders->links() }}</div>

</div>
@endsection