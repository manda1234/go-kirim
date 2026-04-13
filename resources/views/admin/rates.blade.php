@extends('layouts.app')
@section('title','Setting Tarif')
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
  <a href="{{ route('admin.customers') }}" class="bnav-item"><i class="fas fa-users text-xl"></i><span>User</span></a>
  <a href="{{ route('admin.rates') }}" class="bnav-item active"><i class="fas fa-cog text-xl"></i><span>Setting</span></a>
@endsection
@section('content')
<div class="max-w-2xl mx-auto p-4 space-y-4">
  <h1 class="font-black text-xl text-slate-800 pt-2">⚙️ Setting Tarif Layanan</h1>

  <div class="card p-4 bg-amber-50 border-2 border-amber-200 flex items-start gap-3">
    <span class="text-amber-500 text-xl">⚠️</span>
    <p class="text-sm text-amber-800 font-semibold">Perubahan tarif akan berlaku untuk semua pesanan baru. Pesanan yang sedang berjalan tidak terpengaruh.</p>
  </div>

  <form method="POST" action="{{ route('admin.rates.update') }}">
    @csrf

    @foreach([
      ['motor','🏍️ Motor','Cepat & hemat, cocok untuk dokumen dan paket kecil'],
      ['mobil','🚗 Mobil','Kapasitas besar, cocok untuk barang banyak atau antar orang'],
    ] as [$type, $icon, $desc])
    @php $rate = $rates[$type] ?? null; @endphp
    <div class="card p-6">
      <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
        <div class="w-12 h-12 {{ $type  === 'motor' ? 'bg-green-100' : 'bg-blue-100' }} rounded-xl flex items-center justify-center text-2xl">{{ explode(' ',$icon)[0] }}</div>
        <div>
          <h2 class="font-black text-slate-800">{{ $icon }}</h2>
          <p class="text-xs text-slate-400 mt-0.5">{{ $desc }}</p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Tarif per KM (Rp)</label>
          <div class="relative">
            <span class="absolute left-3 top-3 text-slate-400 text-sm">Rp</span>
            <input type="number" name="{{ $type }}_rate_per_km"
              id="{{ $type }}_rate_per_km"
              value="{{ old("{$type}_rate_per_km", $rate?->rate_per_km ?? ($type==='motor' ? 2500 : 4000)) }}"
              class="form-input pl-9" placeholder="2500" min="100" required
              oninput="updatePreview('{{ $type }}')">
          </div>
          <p class="text-xs text-slate-400 mt-1">Biaya per kilometer perjalanan</p>
        </div>
        <div>
          <label class="form-label">Biaya Layanan (Rp)</label>
          <div class="relative">
            <span class="absolute left-3 top-3 text-slate-400 text-sm">Rp</span>
            <input type="number" name="{{ $type }}_service_fee"
              id="{{ $type }}_service_fee"
              value="{{ old("{$type}_service_fee", $rate?->service_fee ?? 2000) }}"
              class="form-input pl-9" placeholder="2000" min="0" required
              oninput="updatePreview('{{ $type }}')">
          </div>
          <p class="text-xs text-slate-400 mt-1">Biaya layanan flat per transaksi</p>
        </div>
        <div>
          <label class="form-label">Komisi Platform (%)</label>
          <div class="relative">
            <input type="number" name="{{ $type }}_platform_commission"
              id="{{ $type }}_platform_commission"
              value="{{ old("{$type}_platform_commission", $rate?->platform_commission ?? 10) }}"
              class="form-input pr-8" placeholder="10" min="0" max="50" step="0.5" required
              oninput="updatePreview('{{ $type }}')">
            <span class="absolute right-3 top-3 text-slate-400 text-sm">%</span>
          </div>
          <p class="text-xs text-slate-400 mt-1">Persentase potongan dari total</p>
        </div>
        <div>
          <label class="form-label">Ongkir Minimum (Rp)</label>
          <div class="relative">
            <span class="absolute left-3 top-3 text-slate-400 text-sm">Rp</span>
            <input type="number" name="{{ $type }}_min_fare"
              id="{{ $type }}_min_fare"
              value="{{ old("{$type}_min_fare", $rate?->min_fare ?? 5000) }}"
              class="form-input pl-9" placeholder="5000" min="0" required
              oninput="updatePreview('{{ $type }}')">
          </div>
          <p class="text-xs text-slate-400 mt-1">Biaya minimum tiap pesanan</p>
        </div>
      </div>

      <!-- Preview — otomatis update via JS -->
      <div class="mt-4 p-3 bg-slate-50 rounded-xl">
        <p class="text-xs font-bold text-slate-500 mb-2">PREVIEW HARGA (untuk 5 km)</p>
        <div class="flex justify-between text-sm">
          <span class="text-slate-500" id="{{ $type }}_preview_label">Ongkir (5 km × Rp {{ number_format($rate?->rate_per_km ?? ($type==='motor'?2500:4000), 0,',','.') }})</span>
          <span class="font-bold" id="{{ $type }}_preview_ongkir">Rp {{ number_format(5 * ($rate?->rate_per_km ?? ($type==='motor'?2500:4000)), 0,',','.') }}</span>
        </div>
        <div class="flex justify-between text-sm mt-1">
          <span class="text-slate-500">Biaya Layanan</span>
          <span class="font-bold" id="{{ $type }}_preview_service">Rp {{ number_format($rate?->service_fee ?? 2000, 0,',','.') }}</span>
        </div>
        <div class="flex justify-between text-sm font-black mt-2 pt-2 border-t border-slate-200">
          <span>Total</span>
          <span class="text-primary-600" id="{{ $type }}_preview_total">Rp {{ number_format(5*($rate?->rate_per_km ?? ($type==='motor'?2500:4000))+($rate?->service_fee ?? 2000), 0,',','.') }}</span>
        </div>
      </div>
    </div>
    @endforeach

    <button type="submit" class="w-full btn-primary text-base shadow-lg shadow-primary-200 py-4">
      💾 Simpan Pengaturan Tarif
    </button>
  </form>
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka || 0).toLocaleString('id-ID');
}

function updatePreview(type) {
    var ratePerKm  = parseFloat(document.getElementById(type + '_rate_per_km').value)  || 0;
    var serviceFee = parseFloat(document.getElementById(type + '_service_fee').value)  || 0;
    var minFare    = parseFloat(document.getElementById(type + '_min_fare').value)     || 0;

    var km        = 5;
    var ongkir    = ratePerKm * km;
    var total     = Math.max(ongkir + serviceFee, minFare);

    document.getElementById(type + '_preview_label').textContent  = 'Ongkir (' + km + ' km × Rp ' + parseInt(ratePerKm).toLocaleString('id-ID') + ')';
    document.getElementById(type + '_preview_ongkir').textContent = formatRupiah(ongkir);
    document.getElementById(type + '_preview_service').textContent = formatRupiah(serviceFee);
    document.getElementById(type + '_preview_total').textContent  = formatRupiah(total);
}
</script>
@endpush