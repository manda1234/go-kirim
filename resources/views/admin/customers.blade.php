@extends('layouts.app')
@section('title','Manajemen Customer')
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
  <a href="{{ route('admin.customers') }}" class="bnav-item active"><i class="fas fa-users text-xl"></i><span>User</span></a>
  <a href="{{ route('admin.rates') }}" class="bnav-item"><i class="fas fa-cog text-xl"></i><span>Setting</span></a>
@endsection
@section('content')
<div class="max-w-4xl mx-auto p-4 space-y-4">
  <div class="flex items-center justify-between pt-2">
    <h1 class="font-black text-xl text-slate-800">👥 Manajemen Customer</h1>
    <span class="badge badge-info">{{ $customers->total() }} total</span>
  </div>

  <!-- Filter -->
  <form method="GET" class="card p-4 flex gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email/telepon..." class="form-input flex-1">
    <select name="status" class="form-select w-36">
      <option value="">Semua</option>
      <option value="active" {{ request('status')==='active'?'selected':'' }}>Aktif</option>
      <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Nonaktif</option>
    </select>
    <button type="submit" class="btn-primary px-5 text-sm">Filter</button>
  </form>

  <div class="card overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden md:table-cell">Telepon</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Order</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase hidden lg:table-cell">Bergabung</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($customers as $c)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3.5">
              <div class="flex items-center gap-3">
                <img src="{{ $c->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
                <div>
                  <p class="font-bold text-slate-800">{{ $c->name }}</p>
                  <p class="text-xs text-slate-400">{{ $c->email }}</p>
                </div>
              </div>
            </td>
            <td class="px-5 py-3.5 text-slate-500 hidden md:table-cell">{{ $c->phone ?? '–' }}</td>
            <td class="px-5 py-3.5 font-bold text-primary-600">{{ $c->orders_as_customer_count }}</td>
            <td class="px-5 py-3.5 text-slate-400 text-xs hidden lg:table-cell">{{ $c->created_at->format('d M Y') }}</td>
            <td class="px-5 py-3.5">
              <span class="badge {{ $c->is_active ? 'badge-success' : 'badge-danger' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </td>
            <td class="px-5 py-3.5">
              <form method="POST" action="{{ route('admin.customers.toggle', $c->id) }}">
                @csrf
                <button type="submit" class="text-xs font-bold {{ $c->is_active ? 'text-red-500 hover:text-red-700' : 'text-primary-600 hover:text-primary-800' }} transition-colors">
                  {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">Tidak ada customer ditemukan</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $customers->links() }}</div>
  </div>
</div>
@endsection
