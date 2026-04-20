<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KirimCepat – Solusi Kirim & Antar</title>

<!-- Alpine.js untuk FAQ accordion -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Google Fonts CDN -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50:  '#f0fdf4',
                        100: '#dcfce7',
                        200: '#bbf7d0',
                        300: '#86efac',
                        500: '#22c55e',
                        600: '#16a34a',
                        700: '#15803d',
                        800: '#166534',
                    }
                },
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'sans-serif']
                }
            }
        }
    }
</script>

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50%       { transform: translateY(-10px); }
    }
    @keyframes pulse-ring {
        0%   { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in-up  { animation: fadeInUp 0.7s ease forwards; }
    .animate-fade-in     { animation: fadeIn 0.7s ease forwards; }
    .animate-float       { animation: float 3s ease-in-out infinite; }
    .animate-marquee {
        animation: marquee 35s linear infinite;
        display: flex;
        width: max-content;
    }
    .animate-marquee:hover { animation-play-state: paused; }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .opacity-0-start { opacity: 0; }

    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(22, 163, 74, 0.15);
    }

    .btn-primary {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.4);
    }
    .btn-primary:active {
        transform: scale(0.97);
    }

    .hero-bg {
        background: linear-gradient(135deg, #15803d 0%, #16a34a 40%, #166534 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-bg::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-bg::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(134, 239, 172, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .stat-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    nav.scrolled {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    /* FAQ accordion transition */
    [x-cloak] { display: none !important; }
</style>
</head>
<body class="bg-white antialiased">

<!-- ─── NAVBAR ─────────────────────────────────────────────── -->
<nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg shadow-primary-200">
                <span class="text-white font-black text-xs">KC</span>
            </div>
            <span class="font-black text-slate-800 text-lg tracking-tight">KirimCepat</span>
        </div>
        <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-500">
            <a href="#layanan"   class="hover:text-primary-600 transition-colors">Layanan</a>
            <a href="#tarif"     class="hover:text-primary-600 transition-colors">Tarif</a>
            <a href="#cara-kerja" class="hover:text-primary-600 transition-colors">Cara Kerja</a>
            <a href="#mitra"     class="hover:text-primary-600 transition-colors">Jadi Mitra</a>
            <a href="#faq"       class="hover:text-primary-600 transition-colors">FAQ</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary-600 transition-colors px-3 py-2 rounded-xl hover:bg-primary-50">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all shadow-md shadow-primary-200 btn-primary">
                Daftar Gratis
            </a>
        </div>
    </div>
</nav>

<!-- ─── HERO ───────────────────────────────────────────────── -->
<section class="hero-bg min-h-screen flex items-center pt-16">
    <div class="max-w-5xl mx-auto px-4 py-24 relative z-10">
        <div class="text-center">
            <!-- Badge -->
            <div class="opacity-0-start animate-fade-in-up inline-flex items-center gap-2 bg-white/15 backdrop-blur border border-white/20 px-4 py-2 rounded-full text-white text-sm font-semibold mb-8">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span>
                Platform Pengiriman #1 di Indonesia
            </div>

            <!-- Headline -->
            <h1 class="opacity-0-start animate-fade-in-up delay-100 text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1] mb-6 tracking-tight">
                Kirim &amp;<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-200 to-green-300">
                    Antar Orang
                </span><br>
                Lebih Mudah
            </h1>

            <!-- Subheadline -->
            <p class="opacity-0-start animate-fade-in-up delay-200 text-green-100 text-lg md:text-xl max-w-xl mx-auto mb-10 leading-relaxed font-medium">
                Satu platform untuk semua kebutuhan pengiriman Anda. Cepat, aman, dan terjangkau di seluruh kota.
            </p>

            <!-- CTA Buttons -->
            <div class="opacity-0-start animate-fade-in-up delay-300 flex flex-wrap gap-4 justify-center mb-16">
                <a href="{{ route('register') }}" class="bg-white hover:bg-green-50 text-primary-700 font-black px-8 py-4 rounded-2xl text-base transition-all shadow-2xl btn-primary inline-flex items-center gap-2">
                    Mulai Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white/30 hover:bg-white/10 text-white font-bold px-8 py-4 rounded-2xl text-base transition-all backdrop-blur inline-flex items-center gap-2">
                    Sudah Punya Akun
                </a>
            </div>

            <!-- Stats -->
            <div class="opacity-0-start animate-fade-in-up delay-400 grid grid-cols-3 gap-4 max-w-lg mx-auto">
                @foreach([
                    ['50K+', 'Pengguna Aktif'],
                    ['500+', 'Mitra Driver'],
                    ['4.9★', 'Rating Aplikasi'],
                ] as [$num, $label])
                <div class="stat-card rounded-2xl p-4 text-center">
                    <div class="text-2xl font-black text-white">{{ $num }}</div>
                    <div class="text-green-200 text-xs font-medium mt-1">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Decorative floating icons -->
    <div class="absolute top-32 left-10 text-4xl animate-float opacity-30 hidden lg:block" style="animation-delay:0s">📦</div>
    <div class="absolute top-48 right-16 text-3xl animate-float opacity-30 hidden lg:block" style="animation-delay:1s">🛵</div>
    <div class="absolute bottom-32 right-24 text-4xl animate-float opacity-30 hidden lg:block" style="animation-delay:0.5s">📍</div>
</section>

<!-- ─── LAYANAN ─────────────────────────────────────────────── -->
<section id="layanan" class="py-24 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Layanan Kami</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Semua Ada di Sini</h2>
            <p class="text-slate-500 mt-3 max-w-md mx-auto">Nikmati berbagai layanan pengiriman dalam satu aplikasi yang mudah digunakan</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-8">
            @foreach([
                [
                    'icon'        => '📦',
                    'title'       => 'Kirim Barang',
                    'desc'        => 'Kirim dokumen, paket, elektronik, dan barang lainnya ke seluruh kota dengan aman dan cepat.',
                    'bg'          => 'bg-emerald-50',
                    'iconBg'      => 'bg-emerald-100',
                    'badgeBg'     => 'bg-emerald-100',
                    'badgeText'   => 'text-emerald-700',
                    'badge'       => 'Pengiriman',
                    'cta'         => 'Mulai Kirim',
                    'accentBar'   => 'bg-emerald-400',
                    'features'    => ['Real-time tracking', 'Asuransi barang', 'Estimasi harga instan'],
                ],
                [
                    'icon'        => '🛵',
                    'title'       => 'Antar Orang',
                    'desc'        => 'Pesan ojek atau taksi online untuk perjalanan harian Anda. Aman, nyaman, dan terpercaya.',
                    'bg'          => 'bg-amber-50',
                    'iconBg'      => 'bg-amber-100',
                    'badgeBg'     => 'bg-amber-100',
                    'badgeText'   => 'text-amber-700',
                    'badge'       => 'Transportasi',
                    'cta'         => 'Pesan Ojek',
                    'accentBar'   => 'bg-amber-400',
                    'features'    => ['Driver terverifikasi', 'Harga transparan', 'Rute terpendek'],
                ],
            ] as $service)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group card-hover">
                <div class="h-1.5 {{ $service['accentBar'] }}"></div>
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-16 h-16 {{ $service['iconBg'] }} rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300">
                            {{ $service['icon'] }}
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $service['badgeBg'] }} {{ $service['badgeText'] }} mt-1">
                            {{ $service['badge'] }}
                        </span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">{{ $service['title'] }}</h3>
                    <p class="text-slate-500 leading-relaxed mb-6 text-sm">{{ $service['desc'] }}</p>
                    <div class="border-t border-slate-100 mb-5"></div>
                    <ul class="space-y-2.5 mb-7">
                        @foreach($service['features'] as $f)
                        <li class="flex items-center gap-2.5 text-sm text-slate-600">
                            <span class="w-4 h-4 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-primary-600 font-bold hover:gap-3 transition-all duration-200 text-sm group/cta">
                        {{ $service['cta'] }}
                        <svg class="w-4 h-4 group-hover/cta:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── TARIF ───────────────────────────────────────────────── -->
<section id="tarif" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Harga</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Tarif Terjangkau</h2>
            <p class="text-slate-500 mt-3">Harga transparan, tidak ada biaya tersembunyi</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach([
                [
                    'icon'      => '🏍️',
                    'type'      => 'Motor',
                    'rate'      => 'Rp 2.500/km',
                    'desc'      => 'Hemat & cepat, cocok untuk pengiriman harian dan jarak dekat',
                    'badge'     => 'Paling Populer',
                    'badgeBg'   => 'bg-primary-100 text-primary-700',
                    'highlight' => true,
                    'perks'     => ['Kapasitas hingga 5 kg', 'Cepat di jalur sempit', 'Hemat BBM'],
                ],
                [
                    'icon'      => '🚗',
                    'type'      => 'Mobil',
                    'rate'      => 'Rp 4.000/km',
                    'desc'      => 'Kapasitas besar, cocok untuk barang banyak dan perjalanan jauh',
                    'badge'     => 'Kapasitas Besar',
                    'badgeBg'   => 'bg-blue-100 text-blue-700',
                    'highlight' => false,
                    'perks'     => ['Kapasitas hingga 50 kg', 'AC & nyaman', 'Cocok barang fragil'],
                ],
            ] as $tier)
            <div class="rounded-3xl p-8 border-2 {{ $tier['highlight'] ? 'border-primary-300 bg-primary-50' : 'border-slate-100 bg-white' }} card-hover relative">
                @if($tier['highlight'])
                <div class="absolute -top-3 left-6">
                    <span class="{{ $tier['badgeBg'] }} text-xs font-black px-3 py-1 rounded-full shadow">{{ $tier['badge'] }}</span>
                </div>
                @endif
                <div class="text-5xl mb-4">{{ $tier['icon'] }}</div>
                <div class="flex items-end gap-3 mb-1">
                    <h3 class="text-2xl font-black text-slate-800">{{ $tier['type'] }}</h3>
                    @if(!$tier['highlight'])
                    <span class="{{ $tier['badgeBg'] }} text-xs font-bold px-2 py-0.5 rounded-lg mb-1">{{ $tier['badge'] }}</span>
                    @endif
                </div>
                <p class="text-3xl font-black text-primary-600 mb-1">{{ $tier['rate'] }}</p>
                <p class="text-slate-500 text-sm mb-5">{{ $tier['desc'] }}</p>
                <ul class="space-y-2 mb-6">
                    @foreach($tier['perks'] as $perk)
                    <li class="flex items-center gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $perk }}
                    </li>
                    @endforeach
                </ul>
                <p class="text-xs text-slate-400">+ Biaya layanan Rp 2.000 per transaksi</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── CARA KERJA ──────────────────────────────────────────── -->
<section id="cara-kerja" class="py-24 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Proses</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Cara Kerja</h2>
            <p class="text-slate-500 mt-3">Pesan dalam hitungan menit</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6">
            @foreach([
                ['1', '📱', 'Pilih Layanan',    'Pilih jenis layanan dan isi detail pesanan Anda'],
                ['2', '💰', 'Konfirmasi Harga',  'Cek estimasi harga dan pilih metode pembayaran'],
                ['3', '🔔', 'Mitra Terima',      'Mitra terdekat mendapat notifikasi & segera menjemput'],
                ['4', '📍', 'Lacak Real-time',   'Pantau posisi mitra secara langsung di aplikasi'],
            ] as [$num, $icon, $title, $desc])
            <div class="text-center group">
                <div class="relative mb-5 flex justify-center">
                    <div class="w-16 h-16 bg-white border-2 border-primary-200 group-hover:border-primary-500 group-hover:bg-primary-600 text-3xl rounded-2xl flex items-center justify-center mx-auto shadow-lg transition-all duration-300 group-hover:shadow-primary-200 group-hover:shadow-xl">
                        <span class="group-hover:scale-125 transition-transform duration-300 inline-block">{{ $icon }}</span>
                    </div>
                    <div class="absolute -top-2 -right-1 md:-right-4 w-6 h-6 bg-primary-600 text-white text-xs font-black rounded-full flex items-center justify-center shadow">
                        {{ $num }}
                    </div>
                </div>
                <h3 class="font-black text-slate-800 mb-2">{{ $title }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── KEUNGGULAN ─────────────────────────────────────────── -->
<section class="py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Mengapa Kami</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Kenapa Pilih KirimCepat?</h2>
            <p class="text-slate-500 mt-3">Kami hadir untuk memberikan pengalaman terbaik</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['⚡', 'Respon Cepat',       'Mitra kami merespon pesanan dalam waktu kurang dari 2 menit.',        'bg-yellow-50',  'bg-yellow-100'],
                ['🔒', 'Aman & Terpercaya',  'Semua mitra telah terverifikasi KTP, SIM, dan STNK resmi.',           'bg-blue-50',    'bg-blue-100'],
                ['💸', 'Harga Transparan',   'Tidak ada biaya tersembunyi, harga terlihat sebelum pesan.',          'bg-green-50',   'bg-green-100'],
                ['📍', 'Lacak Real-time',    'Pantau posisi mitra langsung dari browser Anda kapan saja.',          'bg-purple-50',  'bg-purple-100'],
                ['🎯', 'Akurasi Tinggi',     'Sistem kami memilih mitra terdekat secara otomatis dan cerdas.',      'bg-red-50',     'bg-red-100'],
                ['🏆', 'Bergaransi',         'Barang tidak sampai? Kami siap bertanggung jawab penuh.',             'bg-orange-50',  'bg-orange-100'],
            ] as [$icon, $title, $desc, $bg, $iconBg])
            <div class="{{ $bg }} rounded-2xl p-6 card-hover border border-slate-100">
                <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center text-2xl mb-4">
                    {{ $icon }}
                </div>
                <h3 class="font-black text-slate-800 mb-2">{{ $title }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── STATS COUNTER ───────────────────────────────────────── -->
<section class="py-20 hero-bg relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-white">KirimCepat dalam Angka</h2>
            <p class="text-green-200 mt-2">Dipercaya oleh ribuan pengguna setiap hari</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['50.000+',   'Pengguna Aktif',    '👥'],
                ['500+',      'Mitra Driver',       '🏍️'],
                ['120.000+',  'Pesanan Selesai',    '📦'],
                ['4.9/5',     'Rating Pengguna',    '⭐'],
            ] as [$num, $label, $icon])
            <div class="text-center stat-card rounded-2xl p-6">
                <div class="text-3xl mb-2">{{ $icon }}</div>
                <div class="text-3xl font-black text-white mb-1">{{ $num }}</div>
                <div class="text-green-200 text-sm font-medium">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── TESTIMONI ───────────────────────────────────────────── -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Testimoni</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Kata Mereka</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['Budi Santoso',  'Pengusaha Online',   '⭐⭐⭐⭐⭐', 'Pengiriman selalu tepat waktu! Barang sampai dalam kondisi sempurna. Mitra drivernya ramah dan profesional.', 'BS'],
                ['Siti Rahma',    'Ibu Rumah Tangga',   '⭐⭐⭐⭐⭐', 'Order makanan jadi gampang banget. Harganya transparan, tidak ada biaya kejutan. Recommended!',            'SR'],
                ['Andi Pratama',  'Mahasiswa',           '⭐⭐⭐⭐⭐', 'Tarif paling murah dibanding aplikasi lain. Drivernya cepat datang, aplikasinya mudah dipakai.',            'AP'],
            ] as [$name, $role, $stars, $review, $initials])
            <div class="bg-slate-50 rounded-3xl p-7 card-hover border border-slate-100">
                <div class="text-base mb-4">{{ $stars }}</div>
                <p class="text-slate-700 leading-relaxed text-sm mb-6 italic">"{{ $review }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-black text-sm">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-800 text-sm">{{ $name }}</div>
                        <div class="text-slate-400 text-xs">{{ $role }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── MARQUEE AKTIVITAS ───────────────────────────────────── -->
<section class="py-5 bg-slate-900 overflow-hidden border-y border-slate-800">
    <div class="flex gap-8 animate-marquee whitespace-nowrap">
        @php
        $activities = [
            '📦 Andi berhasil kirim paket ke Malioboro',
            '🛵 Siti memesan ojek ke Bandara',
            '⭐ Budi memberi rating 5 bintang',
            '📦 Reza kirim dokumen ke kantor',
            '🛵 Amanda pesan antar ke kampus',
            '✅ 12 pesanan selesai dalam 1 jam terakhir',
            '📦 Toko Online XYZ kirim 5 paket sekaligus',
            '🏍️ Mitra baru bergabung di Yogyakarta',
            '💰 Bonus mingguan cair untuk 50 mitra',
            '🎉 KirimCepat melayani 1.000 pesanan hari ini',
        ];
        @endphp
        @foreach(array_merge($activities, $activities) as $activity)
        <span class="text-slate-400 text-sm font-medium flex items-center gap-2 shrink-0">
            {{ $activity }}
            <span class="text-slate-700 mx-2">•</span>
        </span>
        @endforeach
    </div>
</section>

<!-- ─── REKRUTMEN MITRA ──────────────────────────────────────── -->
<section id="mitra" class="py-24 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-10 md:p-14 flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1 text-center md:text-left">
                <span class="inline-block bg-primary-500/20 text-primary-400 text-xs font-bold px-3 py-1 rounded-full mb-4">
                    🏍️ Bergabung sebagai Mitra
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">
                    Jadikan Motor Anda<br>
                    <span class="text-primary-400">Sumber Penghasilan</span>
                </h2>
                <p class="text-slate-400 mb-6 leading-relaxed">
                    Bergabung bersama 500+ mitra driver kami. Jam kerja fleksibel, penghasilan tanpa batas, bonus performa setiap minggu.
                </p>
                <div class="flex flex-wrap gap-4 justify-center md:justify-start mb-8">
                    @foreach(['Jam Kerja Bebas', 'Bonus Mingguan', 'Asuransi Mitra', 'Pencairan Cepat'] as $perk)
                    <span class="flex items-center gap-1.5 text-sm text-slate-300">
                        <svg class="w-4 h-4 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $perk }}
                    </span>
                    @endforeach
                </div>
                <a href="{{ route('register') }}?role=mitra"
                   class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-black px-8 py-4 rounded-2xl transition-all shadow-lg btn-primary">
                    Daftar Jadi Mitra Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="flex-shrink-0 grid grid-cols-2 gap-4">
                @foreach([
                    ['Rp 3-8 Juta', 'Penghasilan/Bulan', '💰'],
                    ['500+',        'Mitra Aktif',        '👥'],
                    ['< 2 Menit',   'Respon Pesanan',     '⚡'],
                    ['24 Jam',      'Support Mitra',      '🎧'],
                ] as [$val, $label, $icon])
                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
                    <div class="text-2xl mb-1">{{ $icon }}</div>
                    <div class="text-white font-black text-lg">{{ $val }}</div>
                    <div class="text-slate-400 text-xs mt-0.5">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ─── FAQ ─────────────────────────────────────────────────── -->
<section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">FAQ</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Pertanyaan Umum</h2>
            <p class="text-slate-500 mt-3">Punya pertanyaan? Kami punya jawabannya</p>
        </div>
        <div class="space-y-4">
            @foreach([
                ['Bagaimana cara memesan layanan?',               'Daftar akun, pilih layanan (Kirim Barang / Antar Orang), isi detail pesanan, pilih pembayaran, dan mitra akan segera menjemput.'],
                ['Apakah mitra driver sudah terverifikasi?',       'Ya, semua mitra wajib melalui verifikasi KTP, SIM, dan STNK sebelum bisa menerima pesanan.'],
                ['Metode pembayaran apa saja yang tersedia?',      'Saat ini kami mendukung pembayaran tunai (COD), transfer bank, dan QRIS.'],
                ['Bagaimana jika barang saya rusak atau hilang?',  'Kami memberikan jaminan keamanan barang. Hubungi support kami dalam 1x24 jam untuk proses klaim.'],
                ['Bagaimana cara menjadi mitra driver?',           'Klik Daftar sebagai Mitra, lengkapi data diri dan kendaraan, tunggu verifikasi, dan mulai terima pesanan.'],
                ['Apakah ada biaya pendaftaran untuk mitra?',      'Tidak ada! Pendaftaran mitra sepenuhnya gratis. Kami hanya mengambil komisi kecil per transaksi.'],
                ['Berapa lama estimasi pengiriman?',               'Untuk area kota, rata-rata 30-60 menit. Untuk antar orang, mitra tiba dalam 5-10 menit setelah pesanan diterima.'],
                ['Bisa pesan untuk orang lain (penerima berbeda)?','Bisa! Saat memesan, Anda bisa mengisi data penerima yang berbeda dengan akun Anda.'],
            ] as $i => [$q, $a])
            <div class="border border-slate-200 rounded-2xl overflow-hidden" x-data="{ open: false }">
                <button class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors"
                        @click="open = !open">
                    <span class="font-bold text-slate-800 pr-4">{{ $q }}</span>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="px-6 pb-6">
                    <p class="text-slate-500 leading-relaxed text-sm">{{ $a }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── CTA ────────────────────────────────────────────────── -->
<section class="py-24 hero-bg relative overflow-hidden">
    <div class="max-w-2xl mx-auto px-4 text-center relative z-10">
        <span class="inline-block bg-white/15 border border-white/20 text-white text-sm font-bold px-4 py-2 rounded-full mb-6">
            🚀 Bergabung Sekarang, Gratis!
        </span>
        <h2 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight">
            Siap Merasakan<br>Kemudahan KirimCepat?
        </h2>
        <p class="text-green-100 mb-10 text-lg font-medium">
            Daftar sekarang sebagai Customer atau Mitra Driver
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('register') }}?role=customer"
               class="bg-white text-primary-700 font-black px-8 py-4 rounded-2xl hover:bg-green-50 transition-all shadow-2xl btn-primary inline-flex items-center gap-2">
                🛒 Daftar sebagai Customer
            </a>
            <a href="{{ route('register') }}?role=mitra"
               class="border-2 border-white/30 text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/10 transition-all backdrop-blur inline-flex items-center gap-2">
                🏍️ Daftar sebagai Mitra
            </a>
        </div>
    </div>
</section>

<!-- ─── FOOTER ─────────────────────────────────────────────── -->
<footer class="bg-slate-900 text-slate-400 py-12">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-primary-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-black text-xs">KC</span>
                </div>
                <span class="font-black text-white text-lg">KirimCepat</span>
            </div>
            <div class="flex flex-wrap justify-center gap-6 text-sm">
                <a href="#layanan"    class="hover:text-white transition-colors">Layanan</a>
                <a href="#tarif"      class="hover:text-white transition-colors">Tarif</a>
                <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
                <a href="#mitra"      class="hover:text-white transition-colors">Jadi Mitra</a>
                <a href="#faq"        class="hover:text-white transition-colors">FAQ</a>
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a>
            </div>
        </div>
        <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm">
            <p>© {{ date('Y') }} KirimCepat. Solusi pengiriman terpercaya di Indonesia.</p>
        </div>
    </div>
</footer>

<script>
    // Navbar shadow on scroll
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Scroll animation for cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.card-hover').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        observer.observe(el);
    });
</script>

</body>
</html>