@extends('layouts.app')
@section('title', 'Profil Admin')

@section('sidebar-nav')
  <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
  <a href="{{ route('admin.transactions') }}" class="sidebar-link"><i class="fas fa-receipt w-5"></i> Transaksi</a>
  <a href="{{ route('admin.customers') }}" class="sidebar-link"><i class="fas fa-users w-5"></i> Customer</a>
  <a href="{{ route('admin.mitras') }}" class="sidebar-link"><i class="fas fa-motorcycle w-5"></i> Mitra</a>
  <a href="{{ route('admin.bonus.index') }}" class="sidebar-link "><i class="fas fa-trophy w-5"></i> Bonus Performa</a>
  <a href="{{ route('admin.rates') }}" class="sidebar-link"><i class="fas fa-cog w-5"></i> Setting Tarif</a>
  <a href="{{ route('admin.settings') }}" class="sidebar-link"><i class="fas fa-qrcode w-5"></i> Setting QRIS</a>
@endsection

@push('styles')
<style>
.admin-avatar {
    width: 80px; height: 80px; border-radius: 22px;
    background: linear-gradient(135deg, #0f172a, #334155);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 900; font-size: 1.8rem;
    color: #fff; flex-shrink: 0;
    box-shadow: 0 4px 20px rgba(15,23,42,.25);
}
.form-input {
    width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0;
    border-radius: 12px; font-size: .9rem; background: #fafbfc;
    outline: none; transition: border-color .2s, box-shadow .2s;
    font-family: inherit; color: #0f172a;
}
.form-input:focus { border-color: #0f172a; background: #fff; box-shadow: 0 0 0 3px rgba(15,23,42,.08); }
.form-input:disabled { background: #f8fafc; color: #94a3b8; cursor: not-allowed; }
.form-label { display: block; font-size: .72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }

.tab-btn {
    padding: 8px 18px; border-radius: 10px; font-size: .82rem; font-weight: 700;
    border: none; cursor: pointer; background: transparent; color: #94a3b8;
    transition: all .2s; white-space: nowrap;
}
.tab-btn.active { background: #f1f5f9; color: #0f172a; }
.tab-btn:hover:not(.active) { background: #f8fafc; color: #475569; }

.info-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f8fafc; }
.info-item:last-child { border-bottom: none; }
.info-icon { width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: .9rem; flex-shrink: 0; margin-top: 1px; }
.info-lbl { font-size: .72rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .04em; }
.info-val { font-size: .9rem; font-weight: 600; color: #0f172a; margin-top: 2px; }

.stat-card { background: #f8fafc; border-radius: 16px; padding: 16px; }
.stat-card .num { font-size: 1.6rem; font-weight: 900; line-height: 1; }
.stat-card .lbl { font-size: .72rem; color: #94a3b8; font-weight: 600; margin-top: 5px; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    {{-- HEADER --}}
    <div class="card p-5">
        <div class="flex items-center gap-4">
            <div class="admin-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="font-black text-xl text-slate-800 truncate">{{ auth()->user()->name }}</h1>
                <p class="text-sm text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">
                        <i class="fas fa-user-shield text-xs"></i> Administrator
                    </span>
                    <span class="text-xs text-slate-400">
                        Sejak {{ auth()->user()->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick stats --}}
        <div class="grid grid-cols-3 gap-3 mt-5">
            <div class="stat-card">
                <div class="num text-slate-800">{{ $todayOrders ?? 0 }}</div>
                <div class="lbl">Order Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color:#16a34a">{{ $totalMitras ?? 0 }}</div>
                <div class="lbl">Total Mitra</div>
            </div>
            <div class="stat-card">
                <div class="num" style="color:#7c3aed">{{ $totalCustomers ?? 0 }}</div>
                <div class="lbl">Total Customer</div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="card p-2">
        <div class="flex gap-1 overflow-x-auto">
            <button class="tab-btn active" onclick="switchTab('info', this)">
                <i class="fas fa-user mr-1.5"></i>Info Akun
            </button>
            <button class="tab-btn" onclick="switchTab('edit', this)">
                <i class="fas fa-edit mr-1.5"></i>Edit Profil
            </button>
            <button class="tab-btn" onclick="switchTab('password', this)">
                <i class="fas fa-lock mr-1.5"></i>Password
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-2xl p-4">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm font-semibold rounded-2xl p-4">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
    @endif

    {{-- TAB: INFO --}}
    <div id="tab-info" class="card p-5">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
            <i class="fas fa-user-shield text-slate-600"></i> Detail Akun Admin
        </h2>
        <div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div>
                    <div class="info-lbl">Nama Lengkap</div>
                    <div class="info-val">{{ auth()->user()->name }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="info-lbl">Alamat Email</div>
                    <div class="info-val">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="info-lbl">Nomor HP</div>
                    <div class="info-val">{{ auth()->user()->phone ?? '–' }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="info-lbl">Terdaftar Sejak</div>
                    <div class="info-val">{{ auth()->user()->created_at->isoFormat('D MMMM YYYY') }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="info-lbl">Login Terakhir</div>
                    <div class="info-val">{{ now()->isoFormat('D MMM YYYY, HH:mm') }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="info-lbl">Role</div>
                    <div class="info-val">
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                            <i class="fas fa-user-shield text-xs"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: EDIT PROFIL --}}
    <div id="tab-edit" class="card p-5 hidden">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
            <i class="fas fa-edit text-slate-600"></i> Edit Profil
        </h2>
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="form-input" placeholder="08xxxxxxxxxx" inputmode="tel">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" class="form-input" disabled>
                    <p class="text-xs text-slate-400 mt-1.5">Email tidak dapat diubah.</p>
                </div>
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 font-bold rounded-xl text-sm text-white transition-all active:scale-95"
                        style="background:#0f172a">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- TAB: PASSWORD --}}
    <div id="tab-password" class="card p-5 hidden">
        <h2 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm">
            <i class="fas fa-lock text-slate-600"></i> Ganti Password
        </h2>
        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" required placeholder="Password lama">
                </div>
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="Ulangi password baru">
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                    <p class="text-xs text-amber-700 font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        Setelah ganti password, Anda akan diminta login ulang.
                    </p>
                </div>
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 font-bold rounded-xl text-sm text-white transition-all active:scale-95"
                        style="background:#0f172a">
                    <i class="fas fa-key mr-2"></i>Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-2xl border-2 border-red-100 text-red-500 font-bold text-sm hover:bg-red-50 transition">
            <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
        </button>
    </form>

</div>
@endsection

@push('scripts')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + name).classList.remove('hidden');
    btn.classList.add('active');
}
</script>
@endpush