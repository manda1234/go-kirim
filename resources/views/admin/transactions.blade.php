{{-- =================== admin/transactions.blade.php =================== --}}
@extends('layouts.app')
@section('title','Manajemen Transaksi')
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
  <a href="{{ route('admin.transactions') }}" class="bnav-item active"><i class="fas fa-receipt text-xl"></i><span>Transaksi</span></a>
  <a href="{{ route('admin.customers') }}" class="bnav-item"><i class="fas fa-users text-xl"></i><span>User</span></a>
  <a href="{{ route('admin.rates') }}" class="bnav-item"><i class="fas fa-cog text-xl"></i><span>Setting</span></a>
@endsection
@section('content')
<div class="max-w-4xl mx-auto p-4 space-y-4">
  <h1 class="font-black text-xl text-slate-800 pt-2">Manajemen Transaksi 📋</h1>

  <!-- Filter -->
  <form method="GET" class="card p-4">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/customer..." class="form-input col-span-2 lg:col-span-1">
      <select name="status" class="form-select">
        <option value="">Semua Status</option>
        @foreach(['waiting'=>'Menunggu','accepted'=>'Diterima','picking_up'=>'Menjemput','in_progress'=>'Dalam Perjalanan','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $v=>$l)
          <option value="{{ $v }}" {{ request('status')===$v ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>
      <select name="service_type" class="form-select">
        <option value="">Semua Layanan</option>
        <option value="kirim_barang" {{ request('service_type')==='kirim_barang' ? 'selected' : '' }}>Kirim Barang</option>
        <option value="antar_orang" {{ request('service_type')==='antar_orang' ? 'selected' : '' }}>Antar Orang</option>
        <option value="food_delivery" {{ request('service_type')==='food_delivery' ? 'selected' : '' }}>Food Delivery</option>
      </select>
      <button type="submit" class="btn-primary text-sm">🔍 Filter</button>
    </div>
  </form>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden sm:table-cell">Layanan</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden lg:table-cell">Mitra</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden md:table-cell">Tgl</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($orders as $order)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-bold text-primary-600">{{ $order->order_code }}</td>
            <td class="px-5 py-3 font-semibold">{{ $order->customer?->name }}</td>
            <td class="px-5 py-3 text-slate-500 hidden sm:table-cell">{{ $order->service_label }}</td>
            <td class="px-5 py-3 text-slate-500 hidden lg:table-cell">{{ $order->mitra?->name ?? '–' }}</td>
            <td class="px-5 py-3 font-bold text-primary-600">Rp {{ number_format($order->total_fare,0,',','.') }}</td>
            <td class="px-5 py-3 text-slate-400 text-xs hidden md:table-cell">{{ $order->created_at->format('d M y') }}</td>
            <td class="px-5 py-3"><span class="badge {{ $order->status_badge['class'] }}">{{ $order->status_badge['label'] }}</span></td>
            <td class="px-5 py-3">
              <a href="{{ route('admin.orders.detail', $order->id) }}" class="text-primary-600 hover:text-primary-800 font-bold text-xs">Detail →</a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="px-5 py-12 text-center text-slate-400">Tidak ada transaksi ditemukan</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $orders->links() }}</div>
  </div>
</div>
@endsection