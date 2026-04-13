<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KirimCepat – Solusi Kirim & Antar</title>

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

    .animate-fade-in-up  { animation: fadeInUp 0.7s ease forwards; }
    .animate-fade-in     { animation: fadeIn 0.7s ease forwards; }
    .animate-float       { animation: float 3s ease-in-out infinite; }
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

    .step-connector {
        position: absolute;
        top: 32px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: linear-gradient(to right, #16a34a, #dcfce7);
    }

    nav.scrolled {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
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
            <a href="#layanan" class="hover:text-primary-600 transition-colors">Layanan</a>
            <a href="#tarif" class="hover:text-primary-600 transition-colors">Tarif</a>
            <a href="#cara-kerja" class="hover:text-primary-600 transition-colors">Cara Kerja</a>
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
                    'icon' => '📦',
                    'title' => 'Kirim Barang',
                    'desc' => 'Kirim dokumen, paket, elektronik, dan barang lainnya ke seluruh kota dengan aman dan cepat.',
                    'bg' => 'bg-emerald-50',
                    'iconBg' => 'bg-emerald-100',
                    'iconColor' => 'text-emerald-600',
                    'badgeBg' => 'bg-emerald-100',
                    'badgeText' => 'text-emerald-700',
                    'badge' => 'Pengiriman',
                    'cta' => 'Mulai Kirim',
                    'accentBar' => 'bg-emerald-400',
                    'features' => ['Real-time tracking', 'Asuransi barang', 'Estimasi harga instan'],
                ],
                [
                    'icon' => '🛵',
                    'title' => 'Antar Orang',
                    'desc' => 'Pesan ojek atau taksi online untuk perjalanan harian Anda. Aman, nyaman, dan terpercaya.',
                    'bg' => 'bg-amber-50',
                    'iconBg' => 'bg-amber-100',
                    'iconColor' => 'text-amber-600',
                    'badgeBg' => 'bg-amber-100',
                    'badgeText' => 'text-amber-700',
                    'badge' => 'Transportasi',
                    'cta' => 'Pesan Ojek',
                    'accentBar' => 'bg-amber-400',
                    'features' => ['Driver terverifikasi', 'Harga transparan', 'Rute terpendek'],
                ],
            ] as $service)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden group card-hover">
                {{-- Accent bar di bagian atas --}}
                <div class="h-1.5 {{ $service['accentBar'] }}"></div>

                <div class="p-8">
                    {{-- Header: ikon + badge --}}
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-16 h-16 {{ $service['iconBg'] }} rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300">
                            {{ $service['icon'] }}
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $service['badgeBg'] }} {{ $service['badgeText'] }} mt-1">
                            {{ $service['badge'] }}
                        </span>
                    </div>

                    {{-- Judul & deskripsi --}}
                    <h3 class="text-2xl font-black text-slate-800 mb-2">{{ $service['title'] }}</h3>
                    <p class="text-slate-500 leading-relaxed mb-6 text-sm">{{ $service['desc'] }}</p>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 mb-5"></div>

                    {{-- Fitur --}}
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

                    {{-- CTA --}}
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 text-primary-600 font-bold hover:gap-3 transition-all duration-200 text-sm group/cta">
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
                    'icon' => '🏍️',
                    'type' => 'Motor',
                    'rate' => 'Rp 2.500/km',
                    'desc' => 'Hemat & cepat, cocok untuk pengiriman harian dan jarak dekat',
                    'badge' => 'Paling Populer',
                    'badgeBg' => 'bg-primary-100 text-primary-700',
                    'highlight' => true,
                    'perks' => ['Kapasitas hingga 5 kg', 'Cepat di jalur sempit', 'Hemat BBM'],
                ],
                [
                    'icon' => '🚗',
                    'type' => 'Mobil',
                    'rate' => 'Rp 4.000/km',
                    'desc' => 'Kapasitas besar, cocok untuk barang banyak dan perjalanan jauh',
                    'badge' => 'Kapasitas Besar',
                    'badgeBg' => 'bg-blue-100 text-blue-700',
                    'highlight' => false,
                    'perks' => ['Kapasitas hingga 50 kg', 'AC & nyaman', 'Cocok barang fragil'],
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
                        <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
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
                ['1', '📱', 'Pilih Layanan', 'Pilih jenis layanan dan isi detail pesanan Anda'],
                ['2', '💰', 'Konfirmasi Harga', 'Cek estimasi harga dan pilih metode pembayaran'],
                ['3', '🔔', 'Mitra Terima', 'Mitra terdekat mendapat notifikasi & segera menjemput'],
                ['4', '📍', 'Lacak Real-time', 'Pantau posisi mitra secara langsung di aplikasi'],
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

<!-- ─── TESTIMONI ───────────────────────────────────────────── -->
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-primary-600 font-bold text-sm uppercase tracking-widest">Testimoni</span>
            <h2 class="text-3xl md:text-4xl font-black text-slate-800 mt-2">Kata Mereka</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['Budi Santoso', 'Pengusaha Online', '⭐⭐⭐⭐⭐', 'Pengiriman selalu tepat waktu! Barang sampai dalam kondisi sempurna. Mitra drivernya ramah dan profesional.', 'BS'],
                ['Siti Rahma', 'Ibu Rumah Tangga', '⭐⭐⭐⭐⭐', 'Order makanan jadi gampang banget. Harganya transparan, tidak ada biaya kejutan. Recommended!', 'SR'],
                ['Andi Pratama', 'Mahasiswa', '⭐⭐⭐⭐⭐', 'Tarif paling murah dibanding aplikasi lain. Drivernya cepat datang, aplikasinya mudah dipakai.', 'AP'],
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
            <a href="{{ route('register') }}?role=customer" class="bg-white text-primary-700 font-black px-8 py-4 rounded-2xl hover:bg-green-50 transition-all shadow-2xl btn-primary inline-flex items-center gap-2">
                🛒 Daftar sebagai Customer
            </a>
            <a href="{{ route('register') }}?role=mitra" class="border-2 border-white/30 text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/10 transition-all backdrop-blur inline-flex items-center gap-2">
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
            <div class="flex gap-6 text-sm">
                <a href="#layanan" class="hover:text-white transition-colors">Layanan</a>
                <a href="#tarif" class="hover:text-white transition-colors">Tarif</a>
                <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
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
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Intersection Observer for scroll animations
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