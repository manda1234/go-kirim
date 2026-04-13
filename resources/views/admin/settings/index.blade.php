@extends('layouts.app')
@section('content')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt w-5"></i> Dashboard
    </a>
    <a href="{{ route('admin.transactions') }}"
        class="sidebar-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
        <i class="fas fa-receipt w-5"></i> Transaksi
    </a>
    <a href="{{ route('admin.customers') }}" class="sidebar-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}">
        <i class="fas fa-users w-5"></i> Customer
    </a>
    <a href="{{ route('admin.mitras') }}" class="sidebar-link {{ request()->routeIs('admin.mitras') ? 'active' : '' }}">
        <i class="fas fa-motorcycle w-5"></i> Mitra
    </a>
    <a href="{{ route('admin.rates') }}" class="sidebar-link {{ request()->routeIs('admin.rates') ? 'active' : '' }}">
        <i class="fas fa-cog w-5"></i> Setting Tarif
    </a>
    <a href="{{ route('admin.settings') }}"
        class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="fas fa-qrcode w-5"></i> Setting QRIS
    </a>
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


<div class="max-w-2xl mx-auto space-y-5">

    <h1 class="font-black text-xl text-slate-800">Pengaturan QRIS</h1>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-semibold">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}"
          enctype="multipart/form-data" class="card p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Preview QRIS aktif --}}
        @if($qrisImage)
        <div class="text-center p-4 bg-slate-50 rounded-xl">
            <p class="text-sm text-slate-500 mb-2 font-semibold">QRIS Aktif Saat Ini:</p>
            <img src="{{ asset('storage/' . $qrisImage) }}"
                 class="w-56 h-56 object-contain mx-auto border-2 border-green-200 rounded-xl p-2 bg-white">
        </div>
        @else
        <div class="text-center p-6 border-2 border-dashed border-slate-300 rounded-xl text-slate-400">
            <p class="text-3xl mb-1">📷</p>
            <p class="font-semibold">Belum ada gambar QRIS</p>
        </div>
        @endif

        <div>
            <label class="form-label">Upload Gambar QRIS</label>
            <input type="file" name="qris_image" accept="image/*" class="form-input">
            <p class="text-xs text-slate-400 mt-1">Format JPG/PNG, maks 2MB. Screenshot QRIS dari aplikasi bank.</p>
        </div>

        <div>
            <label class="form-label">Info Rekening Bank (opsional)</label>
            <textarea name="bank_info" rows="3" class="form-input resize-none"
                placeholder="BCA 1234567890 a/n Nama Toko&#10;Mandiri 0987654321 a/n Nama Toko">{{ $bankInfo }}</textarea>
            <p class="text-xs text-slate-400 mt-1">Ditampilkan ke customer sebagai alternatif transfer manual.</p>
        </div>

        <div>
            <label class="form-label">Catatan untuk Customer</label>
            <input type="text" name="qris_note" value="{{ $qrisNote }}" class="form-input"
                   placeholder="Scan QRIS lalu transfer nominal persis sesuai tagihan">
        </div>

        <button type="submit"
                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition">
            Simpan Pengaturan QRIS
        </button>
    </form>
</div>
@endsection