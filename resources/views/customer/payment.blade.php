@extends('layouts.app')
@section('content')

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

<div class="max-w-lg mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.tracking') }}"
           class="w-9 h-9 bg-white rounded-xl border border-slate-200 flex items-center justify-center">
            <i class="fas fa-arrow-left text-slate-600 text-sm"></i>
        </a>
        <div>
            <h1 class="font-black text-lg text-slate-800">
                @if($order->payment_method === 'transfer') Pembayaran Transfer Bank
                @elseif($order->payment_method === 'qris') Pembayaran QRIS
                @else Pembayaran
                @endif
            </h1>
            <p class="text-xs text-slate-400">Order #{{ $order->order_code }}</p>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-semibold flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Ringkasan pesanan --}}
    <div class="card p-5 space-y-2">
        <h3 class="font-bold text-slate-700 mb-3">Ringkasan Pesanan</h3>
        <div class="flex justify-between text-sm">
            <span class="text-slate-500">No. Pesanan</span>
            <span class="font-bold text-slate-800">{{ $order->order_code }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-slate-500">Layanan</span>
            <span class="font-semibold text-slate-800">{{ $order->service_label }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-slate-500">Jarak</span>
            <span class="font-semibold text-slate-800">{{ $order->distance_km }} km</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-slate-500">Metode Bayar</span>
            <span class="font-semibold text-slate-800">{{ $order->payment_method_label }}</span>
        </div>
        <div class="border-t border-slate-100 pt-2 mt-2 flex justify-between items-center">
            <span class="font-black text-slate-800">Total Bayar</span>
            <span class="text-2xl font-black text-green-600">
                Rp {{ number_format($order->total_fare, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         BLOK QRIS — hanya tampil jika payment_method = qris
    ══════════════════════════════════════════════════════════ --}}
    @if($order->payment_method === 'qris')

    <div class="card p-5 text-center">
        <h3 class="font-bold text-slate-700 mb-4">
            <i class="fas fa-qrcode mr-1"></i> Scan QRIS untuk Membayar
        </h3>

        @if($qrisImage)
            <div class="inline-block p-3 bg-white border-2 border-green-100 rounded-2xl shadow-sm mb-4">
                <img src="{{ asset('storage/' . $qrisImage) }}"
                     class="w-60 h-60 object-contain" alt="QRIS">
            </div>
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 mb-3">
                <p class="text-xs text-green-700 font-semibold">
                    Masukkan nominal
                    <strong>Rp {{ number_format($order->total_fare, 0, ',', '.') }}</strong>
                    saat membayar via aplikasi bank / e-wallet
                </p>
            </div>
            @if($qrisNote)
            <p class="text-xs text-slate-500 mb-3">{{ $qrisNote }}</p>
            @endif
        @else
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-12 text-slate-400">
                <p class="text-5xl mb-3">QR</p>
                <p class="font-bold text-slate-500">QRIS belum dikonfigurasi</p>
                <p class="text-xs mt-2">Hubungi admin untuk informasi pembayaran</p>
            </div>
        @endif
    </div>

    {{-- Upload bukti QRIS --}}
    @if($order->isPaid())
        {{-- Sudah dikonfirmasi --}}
        <div class="card p-5 bg-green-50 border border-green-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <p class="font-black text-green-700">Pembayaran QRIS Dikonfirmasi!</p>
                    <p class="text-sm text-green-600">{{ $order->paid_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>

    @elseif($order->isWaitingConfirmation())
        {{-- Sudah upload, menunggu admin --}}
        <div class="card p-5">
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl mb-4">
                <p class="text-sm font-bold text-amber-700 flex items-center gap-2">
                    <i class="fas fa-clock"></i> Menunggu konfirmasi admin
                </p>
                <p class="text-xs text-amber-600 mt-1">
                    Bukti pembayaran QRIS Anda sudah diterima dan sedang diverifikasi.
                </p>
            </div>
            <img src="{{ $order->payment_proof_url }}"
                 class="max-w-full rounded-xl border border-amber-200" alt="Bukti QRIS">
            {{-- Tetap izinkan ganti bukti jika dirasa salah --}}
            <form method="POST"
                  action="{{ route('customer.payment.upload', $order) }}"
                  enctype="multipart/form-data"
                  class="mt-3">
                @csrf
                <input type="file" name="payment_proof" accept="image/*" capture="environment" class="form-input mb-2">
                <button type="submit"
                        class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">
                    <i class="fas fa-redo mr-1"></i> Ganti Bukti QRIS
                </button>
            </form>
        </div>

    @else
        {{-- Belum upload --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-1">
                <i class="fas fa-upload mr-1"></i> Upload Bukti Pembayaran QRIS
            </h3>
            <p class="text-xs text-slate-400 mb-4">
                Setelah scan dan bayar, upload screenshot bukti pembayaran dari aplikasi bank / e-wallet Anda.
            </p>
            @error('payment_proof')
            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm font-semibold mb-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </div>
            @enderror
            <form method="POST"
                  action="{{ route('customer.payment.upload', $order) }}"
                  enctype="multipart/form-data"
                  class="space-y-3">
                @csrf
                <input type="file" name="payment_proof" accept="image/*" capture="environment" class="form-input">
                <p class="text-xs text-slate-400">Screenshot bukti bayar QRIS. Format JPG/PNG, maks 3MB.</p>
                <button type="submit"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-upload"></i> Upload Bukti QRIS
                </button>
            </form>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         BLOK TRANSFER — hanya tampil jika payment_method = transfer
    ══════════════════════════════════════════════════════════ --}}
    @elseif($order->payment_method === 'transfer')

    {{-- Info rekening tujuan --}}
    <div class="card p-5">
        <h3 class="font-bold text-slate-700 mb-3">
            <i class="fas fa-university mr-1"></i> Rekening Tujuan Transfer
        </h3>
        @if($bankInfo)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $bankInfo }}</p>
            </div>
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                <p class="text-xs text-yellow-700 font-semibold">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Transfer tepat <strong>Rp {{ number_format($order->total_fare, 0, ',', '.') }}</strong>
                    agar mudah diverifikasi admin.
                </p>
            </div>
        @else
            <p class="text-sm text-slate-400">Info rekening belum dikonfigurasi. Hubungi admin.</p>
        @endif
    </div>

    {{-- Upload bukti transfer --}}
    @if($order->isPaid())
        {{-- Sudah dikonfirmasi --}}
        <div class="card p-5 bg-green-50 border border-green-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <p class="font-black text-green-700">Pembayaran Transfer Dikonfirmasi!</p>
                    <p class="text-sm text-green-600">{{ $order->paid_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>

    @elseif($order->isWaitingConfirmation())
        {{-- Sudah upload, menunggu admin --}}
        <div class="card p-5">
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl mb-4">
                <p class="text-sm font-bold text-amber-700 flex items-center gap-2">
                    <i class="fas fa-clock"></i> Menunggu konfirmasi admin
                </p>
                <p class="text-xs text-amber-600 mt-1">
                    Bukti transfer Anda sudah diterima dan sedang diverifikasi oleh admin.
                </p>
            </div>
            <img src="{{ $order->payment_proof_url }}"
                 class="max-w-full rounded-xl border border-amber-200" alt="Bukti Transfer">
            {{-- Tetap izinkan ganti bukti jika dirasa salah --}}
            <form method="POST"
                  action="{{ route('customer.payment.upload', $order) }}"
                  enctype="multipart/form-data"
                  class="mt-3">
                @csrf
                <input type="file" name="payment_proof" accept="image/*" capture="environment" class="form-input mb-2">
                <button type="submit"
                        class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">
                    <i class="fas fa-redo mr-1"></i> Ganti Bukti Transfer
                </button>
            </form>
        </div>

    @else
        {{-- Belum upload --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-1">
                <i class="fas fa-upload mr-1"></i> Upload Bukti Transfer
            </h3>
            <p class="text-xs text-slate-400 mb-4">
                Setelah transfer, upload foto atau screenshot struk transfer Anda di sini.
            </p>
            @error('payment_proof')
            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm font-semibold mb-3">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
            </div>
            @enderror
            <form method="POST"
                  action="{{ route('customer.payment.upload', $order) }}"
                  enctype="multipart/form-data"
                  class="space-y-3">
                @csrf
                <input type="file" name="payment_proof" accept="image/*" capture="environment" class="form-input">
                <p class="text-xs text-slate-400">Foto / screenshot struk transfer. Format JPG/PNG, maks 3MB.</p>
                <button type="submit"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-upload"></i> Upload Bukti Transfer
                </button>
            </form>
        </div>
    @endif

    @endif
    {{-- ══ END PAYMENT METHOD BLOCKS ══ --}}

</div>
@endsection