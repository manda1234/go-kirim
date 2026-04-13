@extends('layouts.app')
@section('title', 'Buat Pesanan')

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

@section('bottom-nav')
    <a href="{{ route('customer.dashboard') }}" class="bnav-item">
        <i class="fas fa-home text-xl"></i><span>Beranda</span>
    </a>
    <a href="{{ route('customer.order') }}" class="bnav-item active">
        <i class="fas fa-plus-circle text-xl"></i><span>Pesan</span>
    </a>
    <a href="{{ route('customer.tracking') }}" class="bnav-item">
        <i class="fas fa-map-marker-alt text-xl"></i><span>Tracking</span>
    </a>
    <a href="#" class="bnav-item">
        <i class="fas fa-user text-xl"></i><span>Profil</span>
    </a>
@endsection

@push('styles')
    {{-- UPDATED: Tambah tile provider yang lebih mirip Google Maps --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 16px;
            z-index: 1;
        }

        .leaflet-routing-container {
            display: none !important;
        }

        .map-mode-btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            background: white;
            color: #64748b;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .map-mode-btn.pickup-active {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }

        .map-mode-btn.dest-active {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        .address-box {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            background: white;
            transition: border-color 0.2s;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            min-height: 48px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .address-box:hover { border-color: #16a34a; }
        .address-box.set { border-color: #16a34a; background: #f0fdf4; }
        .address-box .placeholder { color: #94a3b8; font-weight: 500; }

        /* UPDATED: Search dropdown yang lebih polished */
        .search-wrapper {
            position: relative;
        }

        .search-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 2;
        }

        .search-input-clear {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            background: #94a3b8;
            border: none;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 10px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .search-input-clear.show { display: flex; }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.14);
            z-index: 9999;
            max-height: 240px;
            overflow-y: auto;
            display: none;
        }

        .search-dropdown.show { display: block; }

        .search-dropdown-item {
            padding: 11px 14px;
            font-size: 13px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .search-dropdown-item:last-child { border-bottom: none; }

        .search-dropdown-item:hover {
            background: #f0fdf4;
        }

        .search-dropdown-item:hover .dd-name { color: #16a34a; }

        .search-dropdown-item .dd-icon {
            flex-shrink: 0;
            margin-top: 1px;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .search-dropdown-item .dd-text { flex: 1; min-width: 0; }
        .search-dropdown-item .dd-name {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .search-dropdown-item .dd-addr {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-dropdown-loading {
            padding: 16px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* UPDATED: Spinner animasi */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { animation: spin 0.8s linear infinite; display: inline-block; }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            padding: 4px 0;
        }
        .price-row .label { color: rgba(255,255,255,0.75); font-weight: 500; }
        .price-row .value { font-weight: 700; color: white; }

        @keyframes markerPulse {
            0%   { box-shadow: 0 0 0 0 rgba(22,163,74,0.5); }
            70%  { box-shadow: 0 0 0 10px rgba(22,163,74,0); }
            100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
        }

        /* UPDATED: Map attribution yang lebih kecil */
        .leaflet-control-attribution {
            font-size: 10px !important;
            background: rgba(255,255,255,0.8) !important;
            border-radius: 6px !important;
        }

        /* UPDATED: Zoom control styling */
        .leaflet-control-zoom a {
            border-radius: 8px !important;
            font-weight: 700 !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto space-y-5">

        {{-- HEADER --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.dashboard') }}"
                class="w-9 h-9 bg-white rounded-xl border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition">
                <i class="fas fa-arrow-left text-slate-600 text-sm"></i>
            </a>
            <div>
                <h1 class="font-black text-lg text-slate-800">Buat Pesanan</h1>
                <p class="text-xs text-slate-400 font-medium">Tentukan lokasi di peta atau cari alamat</p>
            </div>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="p-4 rounded-xl text-sm font-semibold flex items-start gap-2"
                style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <div>
                    @foreach ($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SERVICE SELECTOR --}}
        <div class="grid grid-cols-2 gap-3">
            @foreach ([['kirim_barang','📦','Kirim Barang','Dokumen, paket'],['antar_orang','🛵','Antar Orang','Motor & Mobil']] as [$val,$icon,$label,$desc])
                <label class="cursor-pointer">
                    <input type="radio" name="service_type_sel" value="{{ $val }}"
                        class="sr-only service-type-radio" {{ $serviceType === $val ? 'checked' : '' }}>
                    <div class="service-type-box card p-4 text-center border-2 transition-all
                        {{ $serviceType === $val ? 'border-green-500 bg-green-50' : 'border-transparent' }}
                        hover:border-green-300">
                        <div class="text-2xl mb-1">{{ $icon }}</div>
                        <p class="font-bold text-xs sm:text-sm text-slate-800">{{ $label }}</p>
                        <p class="text-[10px] text-slate-400">{{ $desc }}</p>
                    </div>
                </label>
            @endforeach
        </div>

        <form method="POST" action="{{ route('customer.order.store') }}" id="orderForm">
            @csrf
            <input type="hidden" name="service_type"         id="service_type_hidden"     value="{{ $serviceType }}">
            <input type="hidden" name="pickup_lat"            id="pickup_lat"               value="">
            <input type="hidden" name="pickup_lng"            id="pickup_lng"               value="">
            <input type="hidden" name="destination_lat"       id="destination_lat"          value="">
            <input type="hidden" name="destination_lng"       id="destination_lng"          value="">
            <input type="hidden" name="pickup_address"        id="pickup_address_val"       value="{{ old('pickup_address') }}">
            <input type="hidden" name="destination_address"   id="destination_address_val"  value="{{ old('destination_address') }}">
            <input type="hidden" name="distance_km"           id="distance_km"              value="{{ old('distance_km','1') }}">

            {{-- MAP SECTION --}}
            <div class="card p-5 space-y-4">
                <h2 class="font-black text-slate-800 flex items-center gap-2">
                    🗺️ Pilih Lokasi
                    {{-- UPDATED: badge gratis --}}
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                          style="background:#dcfce7;color:#166534;">OpenStreetMap · Gratis</span>
                </h2>

                {{-- UPDATED: Search bar dengan autocomplete real-time --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- Pickup Search --}}
                    <div class="search-wrapper">
                        <label class="form-label">🟢 Titik Jemput</label>
                        <div class="search-input-group">
                            <span class="search-input-icon">
                                <i class="fas fa-circle text-green-500" style="font-size:9px"></i>
                            </span>
                            <input type="text" id="searchPickup"
                                   placeholder="Ketik nama tempat atau jalan..."
                                   autocomplete="off"
                                   class="form-input"
                                   style="padding-left:32px; padding-right:72px; border-color:#16a34a;">
                            <button type="button" id="clearPickup" class="search-input-clear" onclick="clearSearch('pickup')">✕</button>
                            <button type="button" onclick="triggerSearch('pickup')"
                                class="absolute right-3 top-2.5 text-xs font-bold px-2 py-1 rounded-lg transition"
                                style="background:#16a34a;color:white;font-size:11px;">
                                Cari
                            </button>
                        </div>
                        <div id="dropdownPickup" class="search-dropdown"></div>
                    </div>

                    {{-- Dest Search --}}
                    <div class="search-wrapper">
                        <label class="form-label">🔴 Titik Tujuan</label>
                        <div class="search-input-group">
                            <span class="search-input-icon">
                                <i class="fas fa-map-marker-alt text-red-500" style="font-size:11px"></i>
                            </span>
                            <input type="text" id="searchDest"
                                   placeholder="Ketik nama tempat atau jalan..."
                                   autocomplete="off"
                                   class="form-input"
                                   style="padding-left:32px; padding-right:72px; border-color:#ef4444;">
                            <button type="button" id="clearDest" class="search-input-clear" onclick="clearSearch('dest')">✕</button>
                            <button type="button" onclick="triggerSearch('dest')"
                                class="absolute right-3 top-2.5 text-xs font-bold px-2 py-1 rounded-lg transition"
                                style="background:#ef4444;color:white;font-size:11px;">
                                Cari
                            </button>
                        </div>
                        <div id="dropdownDest" class="search-dropdown"></div>
                    </div>
                </div>

                {{-- UPDATED: hint teks --}}
                <p class="text-xs text-slate-400 font-medium -mt-1">
                    <i class="fas fa-lightbulb text-yellow-400 mr-1"></i>
                    Ketik minimal 3 huruf → saran muncul otomatis. Atau klik langsung di peta.
                </p>

                {{-- Map Mode Buttons --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Mode Klik Peta:</span>
                    <button type="button" id="btnPickup" onclick="setMapMode('pickup')" class="map-mode-btn pickup-active">
                        <span class="w-3 h-3 rounded-full inline-block"
                            style="background:#22c55e;border:2px solid white;box-shadow:0 0 0 2px #16a34a"></span>
                        Titik Jemput
                    </button>
                    <button type="button" id="btnDest" onclick="setMapMode('dest')" class="map-mode-btn">
                        <span class="w-3 h-3 rounded-full inline-block"
                            style="background:#ef4444;border:2px solid white;box-shadow:0 0 0 2px #dc2626"></span>
                        Titik Tujuan
                    </button>
                    <button type="button" onclick="locateMe()" class="map-mode-btn ml-auto">
                        <i class="fas fa-location-arrow text-blue-500"></i>
                        Lokasi Saya
                    </button>
                </div>

                {{-- Map --}}
                <div id="map" class="shadow-md"></div>

                {{-- Selected Addresses --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <p class="form-label mb-1">Titik Jemput</p>
                        <div id="pickupAddressBox" class="address-box">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:#16a34a"></span>
                            <span class="placeholder" id="pickupAddressText">Belum dipilih – klik peta atau cari alamat</span>
                        </div>
                    </div>
                    <div>
                        <p class="form-label mb-1">Titik Tujuan</p>
                        <div id="destAddressBox" class="address-box">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:#ef4444"></span>
                            <span class="placeholder" id="destAddressText">Belum dipilih – klik peta atau cari alamat</span>
                        </div>
                    </div>
                </div>

                {{-- Distance info --}}
                <div id="distanceBanner" class="hidden rounded-xl p-3 text-sm font-semibold flex items-center gap-2"
                    style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d">
                    <i class="fas fa-route"></i>
                    <span id="distanceText">–</span>
                </div>
            </div>

            {{-- DETAIL BARANG --}}
            <div class="card p-5 space-y-4 mt-4" id="detailBarangSection"
                style="{{ $serviceType !== 'kirim_barang' ? 'display:none' : '' }}">
                <h2 class="font-black text-slate-800 flex items-center gap-2">📦 Detail Barang</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Jenis Barang</label>
                        <select name="item_type" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach (['dokumen'=>'📄 Dokumen','elektronik'=>'📱 Elektronik','makanan'=>'🍱 Makanan','pakaian'=>'👕 Pakaian','obat'=>'💊 Obat-obatan','lainnya'=>'📦 Lainnya'] as $v => $l)
                                <option value="{{ $v }}" {{ old('item_type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" name="item_weight" value="{{ old('item_weight') }}"
                               step="0.1" min="0.1" max="100" class="form-input" placeholder="0.5">
                    </div>
                </div>
                <div>
                    <label class="form-label">Catatan untuk Mitra</label>
                    <textarea name="notes" rows="2" class="form-input resize-none"
                              placeholder="Cth: Barang fragile, tolong hati-hati">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- DETAIL ANTAR --}}
            <div class="card p-5 mt-4" id="detailAntarSection"
                style="{{ $serviceType !== 'antar_orang' ? 'display:none' : '' }}">
                <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2">🛵 Detail Perjalanan</h2>
                <label class="form-label">Jumlah Penumpang</label>
                <select name="passengers" class="form-select">
                    <option value="1">1 Orang</option>
                    <option value="2">2 Orang</option>
                    <option value="3">3 Orang</option>
                </select>
            </div>

            {{-- KENDARAAN & PEMBAYARAN --}}
            <div class="card p-5 space-y-4 mt-4">
                <h2 class="font-black text-slate-800">⚙️ Kendaraan & Pembayaran</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Kendaraan</label>
                        <select name="vehicle_type" id="vehicle_type" class="form-select" onchange="updateEstimate()">
                            <option value="motor">🏍️ Motor – Rp 2.500/km</option>
                            <option value="mobil">🚗 Mobil – Rp 4.000/km</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Metode Pembayaran</label>
                        <input type="hidden" name="payment_method" id="payment_method_input" value="cash">
                        <div class="grid grid-cols-2 gap-2 mt-1">
                            @foreach ([
                                ['cash','💵','Tunai','Bayar langsung','#dcfce7'],
                                ['transfer','🏦','Transfer','Via bank','#eff6ff'],
                                ['qris','📱','QRIS','Scan & bayar','#fdf4ff'],
                                ['e_wallet','💳','E-Wallet','GoPay, OVO, Dana','#fff7ed'],
                            ] as [$val,$emoji,$label,$sub,$bg])
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method_radio" value="{{ $val }}"
                                       class="sr-only payment-radio" {{ $val === 'cash' ? 'checked' : '' }}>
                                <div class="payment-option flex items-center gap-3 p-3 rounded-xl border-2 transition-all
                                    {{ $val === 'cash' ? 'border-green-500 bg-green-50' : 'border-slate-200 bg-white' }}">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                         style="background:{{ $bg }}">
                                        <span style="font-size:18px">{{ $emoji }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xs text-slate-800">{{ $label }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $sub }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ESTIMASI HARGA --}}
            <div class="card p-5 mt-4 text-white" style="background:linear-gradient(135deg,#15803d,#16a34a)">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-lg">💰 Estimasi Harga</h3>
                    <button type="button" onclick="updateEstimate()"
                        class="text-xs font-bold px-3 py-1.5 rounded-lg transition"
                        style="background:rgba(255,255,255,0.2)">
                        <i class="fas fa-sync-alt mr-1"></i> Hitung Ulang
                    </button>
                </div>
                <div class="space-y-2">
                    <div class="price-row">
                        <span class="label">Jarak Tempuh</span>
                        <span class="value" id="estJarak">– km</span>
                    </div>
                    <div class="price-row">
                        <span class="label">Ongkos Kirim</span>
                        <span class="value" id="estOngkir">–</span>
                    </div>
                    <div class="price-row">
                        <span class="label">Biaya Layanan</span>
                        <span class="value" id="estLayanan">Rp 2.000</span>
                    </div>
                    <div style="border-top:1px solid rgba(255,255,255,0.25);margin-top:8px;padding-top:8px" class="price-row">
                        <span style="font-weight:800;font-size:15px;color:white">Total Estimasi</span>
                        <span style="font-weight:900;font-size:22px;color:white" id="estTotal">–</span>
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit" id="submitBtn"
                class="w-full mt-4 text-base font-black py-4 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-lg"
                style="background:#16a34a;color:white;opacity:0.5;cursor:not-allowed" disabled>
                <i class="fas fa-rocket"></i>
                <span id="submitText">Pilih titik jemput & tujuan terlebih dahulu</span>
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <script>
    // ── State ─────────────────────────────────────────────────────────────
    let map, routingControl;
    let pickupMarker = null, destMarker = null;
    let mapMode = 'pickup';
    let pickupLatLng = null, destLatLng = null;

    // UPDATED: Pisahkan timer per input agar tidak saling override
    let timerPickup = null, timerDest = null;

    // ── Icons ─────────────────────────────────────────────────────────────
    const pickupIcon = L.divIcon({
        html: `<div style="width:36px;height:36px;border-radius:50% 50% 50% 0;
                background:#16a34a;border:3px solid white;
                box-shadow:0 3px 12px rgba(0,0,0,0.35);
                transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;">
                <span style="transform:rotate(45deg);font-size:15px">📦</span></div>`,
        className: '', iconSize: [36,36], iconAnchor: [18,36], popupAnchor: [0,-40]
    });

    const destIcon = L.divIcon({
        html: `<div style="width:36px;height:36px;border-radius:50% 50% 50% 0;
                background:#ef4444;border:3px solid white;
                box-shadow:0 3px 12px rgba(0,0,0,0.35);
                transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;">
                <span style="transform:rotate(45deg);font-size:15px">📍</span></div>`,
        className: '', iconSize: [36,36], iconAnchor: [18,36], popupAnchor: [0,-40]
    });

    // ── Init Map ──────────────────────────────────────────────────────────
    function initMap() {
        map = L.map('map', { zoomControl: true }).setView([-7.7956, 110.3695], 13);

        // UPDATED: Gunakan tile CartoDB Voyager — mirip Google Maps, gratis
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20,
        }).addTo(map);

        map.on('click', function(e) {
            if (mapMode === 'pickup') setPickupPoint(e.latlng, null);
            else setDestPoint(e.latlng, null);
        });

        map.getContainer().style.cursor = 'crosshair';
    }

    // ── Set Mode ──────────────────────────────────────────────────────────
    function setMapMode(mode) {
        mapMode = mode;
        document.getElementById('btnPickup').className = mode === 'pickup' ? 'map-mode-btn pickup-active' : 'map-mode-btn';
        document.getElementById('btnDest').className   = mode === 'dest'   ? 'map-mode-btn dest-active'   : 'map-mode-btn';
    }

    // ── Set Pickup Point ──────────────────────────────────────────────────
    function setPickupPoint(latlng, addressStr) {
        pickupLatLng = latlng;
        if (pickupMarker) map.removeLayer(pickupMarker);
        pickupMarker = L.marker(latlng, { icon: pickupIcon, draggable: true })
            .addTo(map)
            .bindPopup('<b style="color:#16a34a">📦 Titik Jemput</b>')
            .openPopup();

        pickupMarker.on('dragend', function(e) {
            pickupLatLng = e.target.getLatLng();
            reverseGeocode(pickupLatLng, 'pickup');
            drawRoute();
        });

        if (addressStr) setPickupAddress(addressStr, latlng);
        else reverseGeocode(latlng, 'pickup');

        if (!destLatLng) setTimeout(() => setMapMode('dest'), 300);
        drawRoute();
        checkReady();
    }

    // ── Set Dest Point ────────────────────────────────────────────────────
    function setDestPoint(latlng, addressStr) {
        destLatLng = latlng;
        if (destMarker) map.removeLayer(destMarker);
        destMarker = L.marker(latlng, { icon: destIcon, draggable: true })
            .addTo(map)
            .bindPopup('<b style="color:#ef4444">📍 Titik Tujuan</b>')
            .openPopup();

        destMarker.on('dragend', function(e) {
            destLatLng = e.target.getLatLng();
            reverseGeocode(destLatLng, 'dest');
            drawRoute();
        });

        if (addressStr) setDestAddress(addressStr, latlng);
        else reverseGeocode(latlng, 'dest');

        drawRoute();
        checkReady();
    }

    // ── Reverse Geocode ───────────────────────────────────────────────────
    function reverseGeocode(latlng, type) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json&addressdetails=1`, {
            headers: { 'User-Agent': 'KirimCepat/1.0', 'Accept-Language': 'id' }
        })
        .then(r => r.json())
        .then(data => {
            const addr = data.display_name || `${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}`;
            if (type === 'pickup') setPickupAddress(addr, latlng);
            else setDestAddress(addr, latlng);
        })
        .catch(() => {
            const addr = `${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}`;
            if (type === 'pickup') setPickupAddress(addr, latlng);
            else setDestAddress(addr, latlng);
        });
    }

    // ── Set Address Display ───────────────────────────────────────────────
    function setPickupAddress(addr, latlng) {
        const shortAddr = addr.split(',').slice(0, 3).join(',');
        document.getElementById('pickupAddressText').textContent = addr;
        document.getElementById('pickupAddressText').className = '';
        document.getElementById('pickupAddressBox').classList.add('set');
        document.getElementById('pickup_address_val').value = addr;
        document.getElementById('pickup_lat').value = latlng.lat;
        document.getElementById('pickup_lng').value = latlng.lng;
        document.getElementById('searchPickup').value = shortAddr;
        document.getElementById('clearPickup').classList.add('show');
        closeDropdown('pickup');
        checkReady();
    }

    function setDestAddress(addr, latlng) {
        const shortAddr = addr.split(',').slice(0, 3).join(',');
        document.getElementById('destAddressText').textContent = addr;
        document.getElementById('destAddressText').className = '';
        document.getElementById('destAddressBox').classList.add('set');
        document.getElementById('destination_address_val').value = addr;
        document.getElementById('destination_lat').value = latlng.lat;
        document.getElementById('destination_lng').value = latlng.lng;
        document.getElementById('searchDest').value = shortAddr;
        document.getElementById('clearDest').classList.add('show');
        closeDropdown('dest');
        checkReady();
    }

    // ── Draw Route ────────────────────────────────────────────────────────
    function drawRoute() {
        if (!pickupLatLng || !destLatLng) return;
        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(pickupLatLng.lat, pickupLatLng.lng),
                L.latLng(destLatLng.lat,   destLatLng.lng),
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: false,
            lineOptions: {
                styles: [
                    { color: '#16a34a', weight: 5, opacity: 0.85 },
                    { color: '#86efac', weight: 3, opacity: 0.5, dashArray: '8,4' },
                ]
            },
            createMarker: () => null,
        }).addTo(map);

        routingControl.on('routesfound', function(e) {
            const route  = e.routes[0];
            const distKm = (route.summary.totalDistance / 1000).toFixed(1);
            document.getElementById('distance_km').value = distKm;
            document.getElementById('distanceBanner').classList.remove('hidden');
            document.getElementById('distanceText').textContent =
                `Jarak: ${distKm} km · Estimasi waktu: ${Math.ceil(route.summary.totalTime / 60)} menit`;
            updateEstimate();
        });

        routingControl.on('routingerror', function() {
            const dist   = map.distance(pickupLatLng, destLatLng);
            const distKm = (dist / 1000).toFixed(1);
            document.getElementById('distance_km').value = distKm;
            document.getElementById('distanceBanner').classList.remove('hidden');
            document.getElementById('distanceText').textContent = `Jarak estimasi: ${distKm} km (garis lurus)`;
            updateEstimate();
        });
    }

    // ── UPDATED: Fungsi search dengan autocomplete + loading state ─────────
    function doSearch(type) {
        const inputId    = type === 'pickup' ? 'searchPickup' : 'searchDest';
        const dropdownId = type === 'pickup' ? 'dropdownPickup' : 'dropdownDest';
        const query      = document.getElementById(inputId).value.trim();
        const dropdown   = document.getElementById(dropdownId);
        const color      = type === 'pickup' ? '#16a34a' : '#ef4444';
        const iconBg     = type === 'pickup' ? '#dcfce7' : '#fee2e2';

        if (query.length < 3) {
            closeDropdown(type);
            return;
        }

        // Tampilkan loading
        dropdown.innerHTML = `
            <div class="search-dropdown-loading">
                <span class="spin" style="display:inline-block">⟳</span>
                Mencari "<strong>${query}</strong>"...
            </div>`;
        dropdown.classList.add('show');

        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=6&addressdetails=1&countrycodes=id`, {
            headers: {
                'User-Agent':       'KirimCepat/1.0',
                'Accept-Language':  'id'
            }
        })
        .then(r => r.json())
        .then(results => {
            if (!results.length) {
                dropdown.innerHTML = `
                    <div class="search-dropdown-loading" style="color:#94a3b8">
                        <i class="fas fa-search-minus"></i>
                        Alamat "<strong>${query}</strong>" tidak ditemukan
                    </div>`;
                return;
            }

            dropdown.innerHTML = '';
            results.forEach(r => {
                // UPDATED: Tampilkan nama tempat & alamat terpisah
                const nameParts = r.display_name.split(', ');
                const placeName = nameParts.slice(0, 2).join(', ');
                const placeAddr = nameParts.slice(2, 5).join(', ');

                const item = document.createElement('div');
                item.className = 'search-dropdown-item';
                item.innerHTML = `
                    <div class="dd-icon" style="background:${iconBg}">
                        <i class="fas fa-map-marker-alt" style="color:${color};font-size:12px"></i>
                    </div>
                    <div class="dd-text">
                        <div class="dd-name">${placeName}</div>
                        <div class="dd-addr">${placeAddr || r.display_name}</div>
                    </div>`;
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault(); // cegah blur trigger duluan
                    const latlng = L.latLng(parseFloat(r.lat), parseFloat(r.lon));
                    if (type === 'pickup') {
                        setPickupPoint(latlng, r.display_name);
                        map.setView(latlng, 16);
                    } else {
                        setDestPoint(latlng, r.display_name);
                        map.setView(latlng, 16);
                    }
                });
                dropdown.appendChild(item);
            });
        })
        .catch(() => {
            dropdown.innerHTML = `
                <div class="search-dropdown-loading" style="color:#ef4444">
                    <i class="fas fa-exclamation-triangle"></i>
                    Gagal terhubung. Coba lagi.
                </div>`;
        });
    }

    // ── UPDATED: Trigger search (tombol Cari) ─────────────────────────────
    function triggerSearch(type) {
        if (type === 'pickup') { clearTimeout(timerPickup); doSearch('pickup'); }
        else                   { clearTimeout(timerDest);   doSearch('dest'); }
    }

    // ── UPDATED: Clear search ─────────────────────────────────────────────
    function clearSearch(type) {
        if (type === 'pickup') {
            document.getElementById('searchPickup').value = '';
            document.getElementById('clearPickup').classList.remove('show');
            closeDropdown('pickup');
        } else {
            document.getElementById('searchDest').value = '';
            document.getElementById('clearDest').classList.remove('show');
            closeDropdown('dest');
        }
        document.getElementById(type === 'pickup' ? 'searchPickup' : 'searchDest').focus();
    }

    function closeDropdown(type) {
        const id = type === 'pickup' ? 'dropdownPickup' : 'dropdownDest';
        document.getElementById(id).classList.remove('show');
    }

    // ── UPDATED: Bind input dengan debounce otomatis per field ────────────
    function bindSearchInput(inputId, type) {
        const input    = document.getElementById(inputId);
        const clearBtn = document.getElementById(type === 'pickup' ? 'clearPickup' : 'clearDest');

        input.addEventListener('input', function() {
            // Tampilkan / sembunyikan tombol clear
            if (this.value.length > 0) clearBtn.classList.add('show');
            else clearBtn.classList.remove('show');

            // Debounce 450ms
            if (type === 'pickup') {
                clearTimeout(timerPickup);
                timerPickup = setTimeout(() => doSearch('pickup'), 450);
            } else {
                clearTimeout(timerDest);
                timerDest = setTimeout(() => doSearch('dest'), 450);
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); triggerSearch(type); }
            if (e.key === 'Escape') closeDropdown(type);
        });

        // UPDATED: Gunakan blur dengan delay agar klik item dropdown tidak ke-cancel
        input.addEventListener('blur', function() {
            setTimeout(() => closeDropdown(type), 200);
        });

        // Klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#' + inputId) &&
                !e.target.closest('#dropdown' + (type === 'pickup' ? 'Pickup' : 'Dest'))) {
                closeDropdown(type);
            }
        });
    }

    // ── Locate Me ─────────────────────────────────────────────────────────
    function locateMe() {
        if (!navigator.geolocation) { alert('Browser tidak mendukung GPS'); return; }
        navigator.geolocation.getCurrentPosition(
            pos => {
                const latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
                map.setView(latlng, 16);
                setPickupPoint(latlng, null);
                setMapMode('dest');
            },
            () => alert('Tidak dapat mengakses lokasi. Pastikan izin GPS diaktifkan.')
        );
    }

    // ── Update Estimate ────────────────────────────────────────────────────
    function updateEstimate() {
        const vehicle  = document.getElementById('vehicle_type').value;
        const distance = parseFloat(document.getElementById('distance_km').value) || 0;
        if (distance <= 0) return;

        const ratePerKm = vehicle === 'motor' ? 2500 : 4000;
        const ongkir    = Math.round(distance * ratePerKm);
        const layanan   = 2000;
        const total     = ongkir + layanan;
        const fmt       = n => 'Rp ' + n.toLocaleString('id-ID');

        document.getElementById('estJarak').textContent  = distance + ' km';
        document.getElementById('estOngkir').textContent = fmt(ongkir);
        document.getElementById('estLayanan').textContent = fmt(layanan);
        document.getElementById('estTotal').textContent  = fmt(total);
    }

    // ── Check Ready ────────────────────────────────────────────────────────
    function checkReady() {
        const btn  = document.getElementById('submitBtn');
        const text = document.getElementById('submitText');
        if (pickupLatLng && destLatLng) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor  = 'pointer';
            text.textContent  = 'Buat Pesanan Sekarang';
        } else if (pickupLatLng) {
            btn.disabled = true;
            btn.style.opacity = '0.5';
            btn.style.cursor  = 'not-allowed';
            text.textContent  = 'Pilih titik tujuan terlebih dahulu';
        } else {
            btn.disabled = true;
            btn.style.opacity = '0.5';
            btn.style.cursor  = 'not-allowed';
            text.textContent  = 'Pilih titik jemput & tujuan terlebih dahulu';
        }
    }

    // ── Payment Radio ──────────────────────────────────────────────────────
    document.querySelectorAll('.payment-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('border-green-500', 'bg-green-50');
                opt.classList.add('border-slate-200', 'bg-white');
            });
            this.nextElementSibling.classList.remove('border-slate-200', 'bg-white');
            this.nextElementSibling.classList.add('border-green-500', 'bg-green-50');
            document.getElementById('payment_method_input').value = this.value;
        });
    });

    // ── Service Type Selector ──────────────────────────────────────────────
    document.querySelectorAll('.service-type-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('service_type_hidden').value = this.value;
            document.querySelectorAll('.service-type-box').forEach(box => {
                box.classList.remove('border-green-500', 'bg-green-50');
                box.classList.add('border-transparent');
            });
            this.nextElementSibling.classList.add('border-green-500', 'bg-green-50');
            this.nextElementSibling.classList.remove('border-transparent');
            document.getElementById('detailBarangSection').style.display = this.value === 'kirim_barang' ? '' : 'none';
            document.getElementById('detailAntarSection').style.display  = this.value === 'antar_orang'  ? '' : 'none';
        });
    });

    // ── Form Validation ────────────────────────────────────────────────────
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        if (!pickupLatLng || !destLatLng) {
            e.preventDefault();
            alert('Silakan pilih titik jemput dan tujuan terlebih dahulu!');
            return;
        }
        if (!document.getElementById('pickup_address_val').value ||
            !document.getElementById('destination_address_val').value) {
            e.preventDefault();
            alert('Alamat belum terdeteksi, tunggu sebentar atau coba klik ulang peta.');
            return;
        }
    });

    // ── Init ───────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        bindSearchInput('searchPickup', 'pickup');
        bindSearchInput('searchDest', 'dest');

        // Restore old values jika ada
        @if (old('pickup_lat') && old('pickup_lng'))
            setPickupPoint(
                L.latLng({{ old('pickup_lat') }}, {{ old('pickup_lng') }}),
                "{{ old('pickup_address') }}"
            );
        @endif
        @if (old('destination_lat') && old('destination_lng'))
            setDestPoint(
                L.latLng({{ old('destination_lat') }}, {{ old('destination_lng') }}),
                "{{ old('destination_address') }}"
            );
        @endif
    });
    </script>
@endpush