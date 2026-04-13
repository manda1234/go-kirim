@extends('layouts.app')
@section('title', 'Profil Saya')

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
    <a href="{{ route('customer.dashboard') }}" class="bnav-item"><i class="fas fa-home text-xl"></i><span>Home</span></a>
    <a href="{{ route('customer.order') }}" class="bnav-item"><i
            class="fas fa-plus-circle text-xl"></i><span>Pesan</span></a>
    <a href="{{ route('customer.tracking') }}" class="bnav-item"><i
            class="fas fa-map-marker-alt text-xl"></i><span>Tracking</span></a>
    <a href="{{ route('customer.profile') }}" class="bnav-item active"><i
            class="fas fa-user text-xl"></i><span>Profil</span></a>
@endsection

@push('styles')
    <style>
        /* ── Variabel ─────────────────────────────────────────── */
        :root {
            --brand: #f97316;
            --brand-dark: #ea580c;
            --brand-soft: #fff7ed;
            --brand-ring: rgba(249, 115, 22, .18);
            --green: #16a34a;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card: #ffffff;
        }

        /* ── Avatar ────────────────────────────────────────────── */
        .avatar-wrap {
            position: relative;
            width: 96px;
            height: 96px;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--brand);
            box-shadow: 0 0 0 5px var(--brand-ring);
            display: block;
            transition: filter .25s;
        }

        .avatar-initial {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            font-weight: 900;
            color: #fff;
            border: 3px solid var(--brand);
            box-shadow: 0 0 0 5px var(--brand-ring);
        }

        .avatar-wrap:hover .avatar-img {
            filter: brightness(.78);
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 11px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .22);
            border: 2px solid #fff;
            transition: background .2s, transform .2s;
        }

        .avatar-edit-btn:hover {
            background: var(--brand-dark);
            transform: scale(1.12);
        }

        /* ── Tab ───────────────────────────────────────────────── */
        .tab-bar {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            padding: 2px;
            scrollbar-width: none;
        }

        .tab-bar::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            background: transparent;
            color: #94a3b8;
            transition: all .2s;
            white-space: nowrap;
        }

        .tab-btn.active {
            background: var(--brand-soft);
            color: var(--brand);
        }

        .tab-btn:hover:not(.active) {
            background: var(--bg);
            color: #475569;
        }

        /* ── Form ─────────────────────────────────────────────── */
        .form-label {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: .9rem;
            background: #fafbfc;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: inherit;
            color: var(--text);
        }

        .form-input:focus {
            border-color: var(--brand);
            background: #fff;
            box-shadow: 0 0 0 3px var(--brand-ring);
        }

        .form-input:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* ── Stat box ─────────────────────────────────────────── */
        .stat-box {
            background: var(--bg);
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
            border: 1px solid var(--border);
        }

        .stat-num {
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--text);
            line-height: 1;
        }

        .stat-lbl {
            font-size: .68rem;
            color: #94a3b8;
            font-weight: 600;
            margin-top: 4px;
        }

        /* ── Order list ───────────────────────────────────────── */
        .order-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            padding: .9rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-row:last-child {
            border-bottom: none;
        }

        .order-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--brand-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .badge {
            font-size: .68rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .badge-completed {
            background: #dcfce7;
            color: #166534;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-active {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ── Tombol utama ─────────────────────────────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 22px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            font-size: .88rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background .2s, transform .1s;
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .btn-primary:active {
            transform: scale(.97);
        }

        /* ── Alert ────────────────────────────────────────────── */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: .84rem;
            font-weight: 600;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .alert-error {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-size: .84rem;
            font-weight: 600;
            border-radius: 14px;
            padding: 14px 16px;
        }

        /* ── Upload spinner ───────────────────────────────────── */
        .upload-spinner {
            display: none;
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, .45);
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .75rem;
        }

        .avatar-wrap.uploading .upload-spinner {
            display: flex;
        }

        .avatar-wrap.uploading .avatar-edit-btn {
            display: none;
        }
    </style>
@endpush

@section('content')
    @php $user = auth()->user(); @endphp

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- ── KARTU HEADER ──────────────────────────────────────── --}}
        <div class="card p-5">
            <div class="flex items-center gap-4">

                {{-- Avatar + upload --}}
                <form id="avatarForm" action="{{ route('customer.profile.photo.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-wrap" id="avatarWrap">
                        @if ($user->photo)
                            <img id="avatarImg" class="avatar-img"
                                src="{{ url('storage/' . $user->photo) . '?v=' . time() }}"
                                alt="Foto {{ $user->name }}">
                        @else
                            <div class="avatar-initial" id="avatarInitial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="avatarImg" class="avatar-img" src="" alt="" style="display:none;">
                        @endif

                        <div class="upload-spinner" id="uploadSpinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>

                        <label for="avatarInput" class="avatar-edit-btn" title="Ganti foto profil">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="avatarInput" name="photo" class="hidden"
                            accept="image/jpeg,image/png,image/webp">
                    </div>
                </form>

                {{-- Info singkat --}}
                <div class="flex-1 min-w-0">
                    <h1 class="font-black text-xl text-slate-800 truncate">{{ $user->name }}</h1>
                    <p class="text-sm text-slate-400 mt-0.5">{{ $user->email }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Bergabung {{ $user->created_at->translatedFormat('F Y') }}
                    </p>

                    {{-- Tombol hapus foto jika ada --}}
                    @if ($user->photo)
                        <form method="POST" action="{{ route('customer.profile.photo.delete') }}" class="mt-2"
                            onsubmit="return confirm('Hapus foto profil?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-semibold transition">
                                <i class="fas fa-trash-alt mr-1"></i>Hapus foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-3 gap-2 mt-5">
                <div class="stat-box">
                    <div class="stat-num">{{ $totalOrders }}</div>
                    <div class="stat-lbl">Total Pesanan</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num" style="color:var(--green)">{{ $completedOrders }}</div>
                    <div class="stat-lbl">Selesai</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num" style="color:var(--brand);font-size:1rem">
                        Rp{{ number_format($totalSpend / 1000, 0, ',', '.') }}K
                    </div>
                    <div class="stat-lbl">Total Spend</div>
                </div>
            </div>
        </div>

        {{-- ── TABS ────────────────────────────────────────────────── --}}
        <div class="card p-2">
            <div class="tab-bar">
                <button class="tab-btn active" onclick="switchTab('info', this)">
                    <i class="fas fa-user mr-1.5"></i>Info
                </button>
                <button class="tab-btn" onclick="switchTab('password', this)">
                    <i class="fas fa-lock mr-1.5"></i>Password
                </button>
                <button class="tab-btn" onclick="switchTab('riwayat', this)">
                    <i class="fas fa-history mr-1.5"></i>Riwayat
                </button>
            </div>
        </div>

        {{-- ── ALERT ───────────────────────────────────────────────── --}}
        @if (session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        {{-- ── TAB: INFO PRIBADI ──────────────────────────────────── --}}
        <div id="tab-info" class="card p-5">
            <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-user" style="color:var(--brand)"></i> Informasi Pribadi
            </h2>
            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Nomor HP</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-input" placeholder="08xxxxxxxxxx" inputmode="tel">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="2" class="form-input" placeholder="Alamat lengkap (opsional)">{{ old('address', $user->address) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-input" disabled>
                        <p class="text-xs text-slate-400 mt-1.5">Email tidak dapat diubah.</p>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TAB: PASSWORD ──────────────────────────────────────── --}}
        <div id="tab-password" class="card p-5 hidden">
            <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-lock" style="color:var(--brand)"></i> Ganti Password
            </h2>
            <form method="POST" action="{{ route('customer.profile.password') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-input" required
                            placeholder="Min. 8 karakter">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TAB: RIWAYAT PESANAN ───────────────────────────────── --}}
        <div id="tab-riwayat" class="card p-5 hidden">
            <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-history" style="color:var(--brand)"></i> Riwayat Pesanan
            </h2>

            @forelse($recentOrders as $order)
                <div class="order-row">
                    <div class="order-icon">
                        @if ($order->service_type === 'food_delivery')
                            🍔
                        @elseif($order->service_type === 'antar_orang')
                            🧑
                        @else
                            📦
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-700 truncate">
                            {{ $order->pickup_address }}
                        </p>
                        <p class="text-xs text-slate-400 truncate">
                            → {{ $order->destination_address }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-sm font-black text-slate-800">
                            Rp{{ number_format($order->total_fare, 0, ',', '.') }}
                        </div>
                        @php
                            $badgeClass = match ($order->status) {
                                'completed' => 'badge-completed',
                                'cancelled' => 'badge-cancelled',
                                'waiting' => 'badge-pending',
                                default => 'badge-active',
                            };
                            $badgeLabel = match ($order->status) {
                                'completed' => 'Selesai',
                                'cancelled' => 'Batal',
                                'waiting' => 'Menunggu',
                                'accepted' => 'Diterima',
                                'picking_up' => 'Dijemput',
                                'in_progress' => 'Dalam Perjalanan',
                                'delivered' => 'Dikirim',
                                default => ucfirst($order->status),
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} mt-1 inline-block">
                            {{ $badgeLabel }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-box-open text-3xl mb-3 block"></i>
                    <p class="text-sm font-semibold">Belum ada pesanan</p>
                </div>
            @endforelse
        </div>

        {{-- ── LOGOUT ──────────────────────────────────────────────── --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-2xl
                       border-2 border-red-100 text-red-500 font-bold text-sm
                       hover:bg-red-50 transition">
                <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
            </button>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        // ── Tab switching ──────────────────────────────────────────
        function switchTab(name, btn) {
            document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + name).classList.remove('hidden');
            btn.classList.add('active');
        }

        // ── Upload foto profil ─────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('avatarInput');
            const form = document.getElementById('avatarForm');
            const wrap = document.getElementById('avatarWrap');
            const img = document.getElementById('avatarImg');
            const initial = document.getElementById('avatarInitial');

            if (!input) return;

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                // Validasi client-side
                const allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if (!allowed.includes(file.type)) {
                    alert('Format file harus JPG, PNG, atau WEBP.');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Preview sebelum upload
                const reader = new FileReader();
                reader.onload = function(ev) {
                    img.src = ev.target.result;
                    img.style.display = 'block';
                    if (initial) initial.style.display = 'none';
                };
                reader.readAsDataURL(file);

                // Tampilkan spinner & submit
                wrap.classList.add('uploading');
                form.submit();
            });
        });
    </script>
@endpush
