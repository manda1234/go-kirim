{{-- resources/views/mitra/profile.blade.php --}}
@extends('layouts.app')
@section('title', 'Profil Mitra')

@section('sidebar-nav')
    <a href="{{ route('mitra.dashboard') }}" class="sidebar-link">
        <i class="fas fa-home w-5"></i> Dashboard
    </a>
    <a href="{{ route('mitra.earnings') }}" class="sidebar-link">
        <i class="fas fa-wallet w-5"></i> Pendapatan
    </a>
    <a href="{{ route('mitra.history') }}" class="sidebar-link">
        <i class="fas fa-history text-xl"></i><span>Riwayat</span>
    </a>
    <a href="{{ route('mitra.profile') }}" class="sidebar-link active">
        <i class="fas fa-user w-5"></i> Profil
    </a>
@endsection

@section('bottom-nav')
    <a href="{{ route('mitra.dashboard') }}" class="bnav-item"><i class="fas fa-home text-xl"></i><span>Home</span></a>
    <a href="{{ route('mitra.earnings') }}" class="bnav-item"><i
            class="fas fa-wallet text-xl"></i><span>Pendapatan</span></a>
    <a href="{{ route('mitra.history') }}" class="bnav-item"><i class="fas fa-history text-xl"></i><span>Riwayat</span></a>
    <a href="{{ route('mitra.profile') }}" class="bnav-item active"><i
            class="fas fa-user text-xl"></i><span>Profil</span></a>
@endsection

@push('styles')
    <style>
        /* ── Variabel ─────────────────────────────────────────── */
        :root {
            --brand: #16a34a;
            --brand-dark: #15803d;
            --brand-soft: #f0fdf4;
            --brand-ring: rgba(22, 163, 74, .18);
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
            background: linear-gradient(135deg, #16a34a, #4ade80);
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

        .upload-spinner {
            display: none;
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, .42);
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .8rem;
        }

        .avatar-wrap.uploading .upload-spinner {
            display: flex;
        }

        .avatar-wrap.uploading .avatar-edit-btn {
            display: none;
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

        /* ── Stat ─────────────────────────────────────────────── */
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

        /* ── Status chip ──────────────────────────────────────── */
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-verified {
            background: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ── Doc upload ───────────────────────────────────────── */
        .doc-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            align-items: start;
        }

        @media (max-width: 640px) {
            .doc-grid {
                grid-template-columns: 1fr;
            }
        }

        .doc-input-wrap {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .doc-upload-box {
            border: 2px dashed var(--border);
            border-radius: 14px;
            padding: 20px 16px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafbfc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 140px;
            width: 100%;
        }

        .doc-upload-box:hover {
            border-color: var(--brand);
            background: var(--brand-soft);
        }

        .doc-preview {
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* ── Vehicle selector ─────────────────────────────────── */
        .vehicle-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 2px solid var(--border);
            cursor: pointer;
            transition: all .2s;
            background: #fff;
        }

        .vehicle-card.selected {
            border-color: var(--brand);
            background: var(--brand-soft);
        }

        /* ── Tombol ───────────────────────────────────────────── */
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
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $profile = $user->mitraProfile;
    @endphp

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- ── KARTU HEADER ──────────────────────────────────────── --}}
        <div class="card p-5">
            <div class="flex items-center gap-4">

                {{-- Avatar + upload --}}
                <form id="avatarForm" action="{{ route('mitra.profile.photo.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-wrap" id="avatarWrap">
                        @if ($user->photo)
                            <img id="avatarImg" class="avatar-img"
                                src="{{ url('storage/' . $user->photo) . '?v=' . time() }}" alt="Foto {{ $user->name }}">
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

                {{-- Info + status --}}
                <div class="flex-1 min-w-0">
                    <h1 class="font-black text-xl text-slate-800 truncate">{{ $user->name }}</h1>
                    <p class="text-sm text-slate-400 mt-0.5">{{ $user->phone ?? $user->email }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if ($profile)
                            <span class="status-chip status-{{ $profile->status ?? 'pending' }}">
                                @if (($profile->status ?? '') === 'verified')
                                    ✓ Terverifikasi
                                @elseif(($profile->status ?? '') === 'rejected')
                                    ✕ Ditolak
                                @else
                                    ⏳ Menunggu Verifikasi
                                @endif
                            </span>
                        @endif
                        <span class="text-xs text-slate-400">
                            Bergabung {{ $user->created_at->translatedFormat('M Y') }}
                        </span>
                    </div>

                    {{-- Hapus foto --}}
                    @if ($user->photo)
                        <form method="POST" action="{{ route('mitra.profile.photo.delete') }}" class="mt-2"
                            onsubmit="return confirm('Hapus foto profil?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-semibold transition">
                                <i class="fas fa-trash-alt mr-1"></i>Hapus foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-2 mt-5">
                <div class="stat-box">
                    <div class="stat-num">{{ $profile?->total_trips ?? 0 }}</div>
                    <div class="stat-lbl">Trip</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num" style="color:#f59e0b">
                        ⭐ {{ number_format($profile?->rating ?? 5.0, 1) }}
                    </div>
                    <div class="stat-lbl">Rating</div>
                </div>
                <div class="stat-box col-span-2">
                    <div class="stat-num" style="color:var(--brand);font-size:1.05rem">
                        Rp{{ number_format(($profile?->total_earnings ?? 0) / 1000, 0, ',', '.') }}K
                    </div>
                    <div class="stat-lbl">Total Pendapatan</div>
                </div>
            </div>
        </div>

        {{-- ── TABS ────────────────────────────────────────────────── --}}
        <div class="card p-2">
            <div class="tab-bar">
                <button class="tab-btn active" onclick="switchTab('info', this)">
                    <i class="fas fa-user mr-1.5"></i>Info
                </button>
                <button class="tab-btn" onclick="switchTab('kendaraan', this)">
                    <i class="fas fa-motorcycle mr-1.5"></i>Kendaraan
                </button>
                <button class="tab-btn" onclick="switchTab('dokumen', this)">
                    <i class="fas fa-id-card mr-1.5"></i>Dokumen
                </button>
                <button class="tab-btn" onclick="switchTab('password', this)">
                    <i class="fas fa-lock mr-1.5"></i>Password
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
            <form method="POST" action="{{ route('mitra.profile.update') }}">
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
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-input" disabled>
                        <p class="text-xs text-slate-400 mt-1.5">Email tidak dapat diubah.</p>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TAB: KENDARAAN ─────────────────────────────────────── --}}
        <div id="tab-kendaraan" class="card p-5 hidden">
            <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-motorcycle" style="color:var(--brand)"></i> Data Kendaraan
            </h2>
            <form method="POST" action="{{ route('mitra.profile.vehicle') }}">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Jenis Kendaraan</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['motor' => ['🏍️', 'Motor'], 'mobil' => ['🚗', 'Mobil']] as $val => $info)
                                <label
                                    class="vehicle-card {{ ($profile?->vehicle_type ?? 'motor') === $val ? 'selected' : '' }}"
                                    id="vtype-{{ $val }}-label" onclick="selectVtype('{{ $val }}')">
                                    <input type="radio" name="vehicle_type" value="{{ $val }}"
                                        class="hidden"
                                        {{ ($profile?->vehicle_type ?? 'motor') === $val ? 'checked' : '' }}>
                                    <span class="text-2xl">{{ $info[0] }}</span>
                                    <span class="font-bold text-sm text-slate-700">{{ $info[1] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Merk Kendaraan</label>
                            <input type="text" name="vehicle_brand" class="form-input"
                                value="{{ old('vehicle_brand', $profile?->vehicle_brand) }}"
                                placeholder="Honda, Yamaha, Toyota...">
                        </div>
                        <div>
                            <label class="form-label">Plat Nomor</label>
                            <input type="text" name="vehicle_plate" class="form-input"
                                value="{{ old('vehicle_plate', $profile?->vehicle_plate) }}" placeholder="AB 1234 CD"
                                style="text-transform:uppercase">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Kendaraan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TAB: DOKUMEN ───────────────────────────────────────── --}}
        <div id="tab-dokumen" class="card p-5 hidden">
            <h2 class="font-black text-slate-800 mb-1 flex items-center gap-2 text-sm">
                <i class="fas fa-id-card" style="color:var(--brand)"></i> Dokumen Identitas
            </h2>
            <p class="text-xs text-slate-400 mb-5">Digunakan untuk verifikasi akun mitra.</p>

            <form method="POST" action="{{ route('mitra.profile.documents') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-6">

                    {{-- KTP --}}
                    <div>
                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">KTP</p>
                        <div class="doc-grid">
                            <div class="doc-input-wrap">
                                <label class="form-label">Nomor KTP</label>
                                <input type="text" name="ktp_number" class="form-input"
                                    value="{{ old('ktp_number', $profile?->ktp_number) }}" placeholder="16 digit NIK"
                                    maxlength="16" inputmode="numeric">
                                <p class="text-xs text-slate-400">Masukkan 16 digit NIK sesuai KTP.</p>
                            </div>
                            <div class="doc-input-wrap">
                                <label class="form-label">Foto KTP</label>
                                <label class="doc-upload-box" for="ktpPhoto">
                                    @if ($profile?->ktp_photo)
                                        <img src="{{ Storage::url($profile->ktp_photo) }}" class="doc-preview"
                                            id="ktpPreviewImg">
                                        <p class="text-xs text-slate-400 mt-2">Klik untuk ganti</p>
                                    @else
                                        <i class="fas fa-id-card text-3xl mb-2" style="color:#cbd5e1" id="ktpIcon"></i>
                                        <p class="text-xs font-bold text-slate-500">Upload foto KTP</p>
                                        <p class="text-xs text-slate-400 mt-1">JPG / PNG · maks 2MB</p>
                                    @endif
                                </label>
                                <input type="file" id="ktpPhoto" name="ktp_photo" class="hidden" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    {{-- SIM --}}
                    <div>
                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">SIM</p>
                        <div class="doc-grid">
                            <div class="doc-input-wrap">
                                <label class="form-label">Nomor SIM</label>
                                <input type="text" name="sim_number" class="form-input"
                                    value="{{ old('sim_number', $profile?->sim_number) }}" placeholder="Nomor SIM"
                                    inputmode="numeric">
                                <p class="text-xs text-slate-400">Nomor SIM yang masih berlaku.</p>
                            </div>
                            <div class="doc-input-wrap">
                                <label class="form-label">Foto SIM</label>
                                <label class="doc-upload-box" for="simPhoto">
                                    @if ($profile?->sim_photo)
                                        <img src="{{ Storage::url($profile->sim_photo) }}" class="doc-preview"
                                            id="simPreviewImg">
                                        <p class="text-xs text-slate-400 mt-2">Klik untuk ganti</p>
                                    @else
                                        <i class="fas fa-id-badge text-3xl mb-2" style="color:#cbd5e1"
                                            id="simIcon"></i>
                                        <p class="text-xs font-bold text-slate-500">Upload foto SIM</p>
                                        <p class="text-xs text-slate-400 mt-1">JPG / PNG · maks 2MB</p>
                                    @endif
                                </label>
                                <input type="file" id="simPhoto" name="sim_photo" class="hidden" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload"></i> Upload Dokumen
                    </button>
                </div>
            </form>
        </div>

        {{-- ── TAB: PASSWORD ──────────────────────────────────────── --}}
        <div id="tab-password" class="card p-5 hidden">
            <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
                <i class="fas fa-lock" style="color:var(--brand)"></i> Ganti Password
            </h2>
            <form method="POST" action="{{ route('mitra.profile.password') }}">
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

        // ── Vehicle type selector ──────────────────────────────────
        function selectVtype(val) {
            ['motor', 'mobil'].forEach(function(v) {
                var card = document.getElementById('vtype-' + v + '-label');
                var radio = card ? card.querySelector('input[type=radio]') : null;
                if (!card) return;
                if (v === val) {
                    card.classList.add('selected');
                    if (radio) radio.checked = true;
                } else {
                    card.classList.remove('selected');
                }
            });
        }

        // ── Init ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {

            // Vehicle highlight awal
            var checkedVtype = document.querySelector('[name=vehicle_type]:checked');
            if (checkedVtype) selectVtype(checkedVtype.value);

            // ── Upload foto profil ─────────────────────────────────
            var avatarInput = document.getElementById('avatarInput');
            var avatarForm = document.getElementById('avatarForm');
            var avatarWrap = document.getElementById('avatarWrap');
            var avatarImg = document.getElementById('avatarImg');
            var avatarInit = document.getElementById('avatarInitial');

            if (avatarInput) {
                avatarInput.addEventListener('change', function() {
                    var file = this.files[0];
                    if (!file) return;

                    var allowed = ['image/jpeg', 'image/png', 'image/webp'];
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

                    // Preview dulu
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        avatarImg.src = ev.target.result;
                        avatarImg.style.display = 'block';
                        if (avatarInit) avatarInit.style.display = 'none';
                    };
                    reader.readAsDataURL(file);

                    // Spinner & submit
                    avatarWrap.classList.add('uploading');
                    avatarForm.submit();
                });
            }

            // ── Preview dokumen KTP & SIM ──────────────────────────
            function previewDoc(inputId, labelFor) {
                var input = document.getElementById(inputId);
                if (!input) return;
                input.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var label = document.querySelector('label[for="' + labelFor + '"]');
                        if (!label) return;
                        label.innerHTML =
                            '<img src="' + ev.target.result + '" class="doc-preview">' +
                            '<p class="text-xs text-slate-400 mt-2">Klik untuk ganti</p>';
                    };
                    reader.readAsDataURL(file);
                });
            }

            previewDoc('ktpPhoto', 'ktpPhoto');
            previewDoc('simPhoto', 'simPhoto');
        });
    </script>
@endpush
