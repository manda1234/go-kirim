<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar – KirimCepat</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { height: 100%; }

body {
    font-family: 'DM Sans', sans-serif;
    background: #0d1a0f;
    display: flex;
    min-height: 100%;
    overflow-x: hidden;
}

:root {
    --green: #22c55e;
    --green-deep: #16a34a;
    --text: #0f172a;
    --muted: #64748b;
    --border: #e2e8f0;
}

/* ══ LEFT PANEL ════════════════════════════════════════════════ */
.left-panel {
    width: 46%;
    background: linear-gradient(155deg, #052e16 0%, #0d3321 35%, #14532d 65%, #166534 100%);
    position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 50px 44px;
}

/* Animated orbs */
.bg-orb { position: absolute; border-radius: 50%; filter: blur(70px); pointer-events: none; }
.orb-1 {
    width: 420px; height: 420px;
    background: radial-gradient(circle, rgba(34,197,94,.22) 0%, transparent 70%);
    top: -110px; right: -90px;
    animation: fOrb 9s ease-in-out infinite;
}
.orb-2 {
    width: 320px; height: 320px;
    background: radial-gradient(circle, rgba(22,163,74,.17) 0%, transparent 70%);
    bottom: -70px; left: -70px;
    animation: fOrb 11s ease-in-out infinite reverse;
}
@keyframes fOrb {
    0%,100% { transform: translate(0,0) scale(1); }
    33%  { transform: translate(-14px,-18px) scale(1.05); }
    66%  { transform: translate(11px,13px) scale(0.95); }
}

/* Dot pattern overlay */
.left-panel::before {
    content: ''; position: absolute; inset: 0;
    background-image: radial-gradient(rgba(255,255,255,.055) 1px, transparent 1px);
    background-size: 28px 28px; pointer-events: none;
}

.left-content {
    position: relative; z-index: 2;
    text-align: center;
    max-width: 360px; width: 100%;
}

/* Logo badge */
.logo-badge {
    display: inline-flex; align-items: center; gap: 12px;
    background: rgba(255,255,255,.09); border: 1px solid rgba(255,255,255,.13);
    backdrop-filter: blur(14px); border-radius: 16px;
    padding: 10px 22px; margin-bottom: 36px;
    animation: aUp .6s ease both;
}
.logo-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #22c55e, #4ade80);
    border-radius: 9px; display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 800; font-size: 12px;
    color: #052e16; box-shadow: 0 4px 12px rgba(34,197,94,.38);
    flex-shrink: 0;
}
.logo-name { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.15rem; color: #fff; white-space: nowrap; }

/* Headline */
.left-h1 {
    font-family: 'Syne', sans-serif; font-weight: 800;
    font-size: clamp(1.35rem, 2.2vw, 1.9rem);
    color: #fff; line-height: 1.22; margin-bottom: 10px;
    animation: aUp .7s ease .1s both;
}
.left-h1 span { color: #86efac; }
.left-p {
    color: rgba(255,255,255,.56); font-size: .86rem;
    line-height: 1.6; animation: aUp .7s ease .2s both;
}

/* Steps */
.steps-box { margin-top: 28px; text-align: left; }
.step-row {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 13px 0;
    animation: aUp .7s ease both;
}
.step-row:nth-child(1) { animation-delay: .15s; }
.step-row:nth-child(2) { animation-delay: .25s; border-top: 1px solid rgba(255,255,255,.08); }
.step-row:nth-child(3) { animation-delay: .35s; border-top: 1px solid rgba(255,255,255,.08); }
.step-num {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: rgba(34,197,94,.2); border: 1.5px solid rgba(34,197,94,.35);
    color: #86efac; font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: .8rem;
    display: flex; align-items: center; justify-content: center;
}
.step-ico { font-size: 1.25rem; flex-shrink: 0; margin-top: 5px; }
.step-txt h4 { color: #fff; font-weight: 700; font-size: .86rem; margin-bottom: 2px; }
.step-txt p  { color: rgba(255,255,255,.52); font-size: .76rem; line-height: 1.5; }

/* Stats row */
.stats-row {
    display: flex; gap: 10px; margin-top: 22px;
    animation: aUp .7s ease .5s both;
}
.stat-box {
    flex: 1;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px; padding: 12px 10px; text-align: center;
}
.stat-n { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: #86efac; }
.stat-l { font-size: .68rem; color: rgba(255,255,255,.52); margin-top: 3px; }

/* ══ RIGHT PANEL ═══════════════════════════════════════════════ */
.right-panel {
    flex: 1; background: #f8fafc;
    display: flex; align-items: flex-start; justify-content: center;
    padding: 32px; position: relative;
}
.right-panel::before {
    content: ''; position: absolute; inset: 0;
    background-image: radial-gradient(rgba(22,163,74,.038) 1px, transparent 1px);
    background-size: 24px 24px; pointer-events: none;
}

.form-box {
    width: 100%; max-width: 480px;
    position: relative; z-index: 1;
    animation: aUp .6s ease .1s both;
    padding: 32px 0 48px;
}

/* Form header */
.form-head { margin-bottom: 24px; }
.form-head h1 {
    font-family: 'Syne', sans-serif; font-weight: 800;
    font-size: clamp(1.5rem, 3vw, 1.85rem);
    color: var(--text); line-height: 1.2; margin-bottom: 6px;
}
.form-head p { color: var(--muted); font-size: .88rem; line-height: 1.5; }

/* Alerts */
.alert-err {
    background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 14px;
    padding: 12px 16px; margin-bottom: 18px;
    font-size: .84rem; color: #b91c1c; font-weight: 500;
}
.alert-err ul { list-style: none; }
.alert-err li::before { content: '• '; }

/* Role selector */
.role-wrap { margin-bottom: 20px; }
.role-label {
    font-size: .72rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .65px;
    margin-bottom: 10px; display: block;
}
.role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.role-card { cursor: pointer; }
.role-card input { display: none; }
.role-box {
    border: 2px solid var(--border); border-radius: 16px;
    padding: 14px 12px; text-align: center; background: #fff;
    transition: all .2s; position: relative; overflow: hidden;
}
.role-box::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(22,163,74,.05), transparent);
    opacity: 0; transition: opacity .2s;
}
.role-card:hover .role-box { border-color: #86efac; }
.role-card input:checked ~ .role-box {
    border-color: var(--green-deep);
    background: linear-gradient(135deg, #f0fdf4, #fff);
    box-shadow: 0 4px 16px rgba(22,163,74,.15);
}
.role-card input:checked ~ .role-box::before { opacity: 1; }
.role-check {
    position: absolute; top: 8px; right: 8px;
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--green-deep); color: #fff;
    font-size: 10px; font-weight: 900;
    display: none; align-items: center; justify-content: center;
}
.role-card input:checked ~ .role-box .role-check { display: flex; }
.role-emoji { font-size: 1.5rem; margin-bottom: 5px; }
.role-name  { font-weight: 700; font-size: .86rem; color: var(--text); }
.role-desc  { font-size: .7rem; color: var(--muted); margin-top: 2px; }

/* Fields */
.fg { margin-bottom: 14px; }
.fl {
    display: block; font-size: .72rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px;
}
.fw { position: relative; }
.fi {
    width: 100%; padding: 12px 14px 12px 44px;
    border: 2px solid var(--border); border-radius: 13px;
    font-family: 'DM Sans', sans-serif; font-size: .88rem;
    color: var(--text); background: #fff; outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.fi:focus {
    border-color: var(--green-deep);
    box-shadow: 0 0 0 4px rgba(22,163,74,.1);
}
.fi::placeholder { color: #c8d0db; }
.fi-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    color: #b0bac8; pointer-events: none; transition: color .2s;
    display: flex; align-items: center;
}
.fw:focus-within .fi-icon { color: var(--green-deep); }
.fi-eye {
    position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: #b0bac8; transition: color .2s; display: flex; align-items: center;
}
.fi-eye:hover { color: var(--green-deep); }

/* Two-col grid for fields — 1 col mobile, 2 col sm+ */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
}

/* ── Vehicle Type Field ──────────────────────────────────────── */
.vehicle-wrap {
    margin-bottom: 14px;
    overflow: hidden;
    max-height: 0;
    opacity: 0;
    transition: max-height .35s ease, opacity .3s ease, margin .3s ease;
}
.vehicle-wrap.show {
    max-height: 120px;
    opacity: 1;
}
.vehicle-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.vehicle-card { cursor: pointer; }
.vehicle-card input { display: none; }
.vehicle-box {
    border: 2px solid var(--border); border-radius: 13px;
    padding: 11px 10px; text-align: center; background: #fff;
    transition: all .2s; position: relative;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.vehicle-card:hover .vehicle-box { border-color: #86efac; }
.vehicle-card input:checked ~ .vehicle-box {
    border-color: var(--green-deep);
    background: linear-gradient(135deg, #f0fdf4, #fff);
    box-shadow: 0 4px 14px rgba(22,163,74,.15);
}
.vehicle-check {
    position: absolute; top: 6px; right: 6px;
    width: 17px; height: 17px; border-radius: 50%;
    background: var(--green-deep); color: #fff;
    font-size: 9px; font-weight: 900;
    display: none; align-items: center; justify-content: center;
}
.vehicle-card input:checked ~ .vehicle-box .vehicle-check { display: flex; }
.vehicle-ico  { font-size: 1.3rem; }
.vehicle-name { font-weight: 700; font-size: .84rem; color: var(--text); }

/* Password strength */
.pwd-strength { margin-top: 6px; }
.strength-bar { display: flex; gap: 4px; margin-bottom: 4px; }
.s-seg { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background .3s; }
.s-seg.weak   { background: #ef4444; }
.s-seg.ok     { background: #f59e0b; }
.s-seg.good   { background: #22c55e; }
.s-seg.strong { background: #16a34a; }
.s-label { font-size: .7rem; color: var(--muted); }

/* Terms */
.terms-row {
    display: flex; align-items: flex-start; gap: 10px;
    margin: 16px 0 18px; font-size: .84rem; color: var(--muted);
}
.terms-chk {
    width: 18px; height: 18px; border: 2px solid var(--border); border-radius: 6px;
    appearance: none; cursor: pointer; position: relative;
    transition: all .2s; background: #fff; flex-shrink: 0; margin-top: 2px;
}
.terms-chk:checked { background: var(--green-deep); border-color: var(--green-deep); }
.terms-chk:checked::after {
    content: '✓'; position: absolute; top: -1px; left: 2px;
    color: #fff; font-size: 11px; font-weight: 900;
}
.terms-row a { color: var(--green-deep); font-weight: 600; text-decoration: none; }
.terms-row a:hover { text-decoration: underline; }

/* Submit button */
.btn-reg {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, #15803d, #22c55e);
    color: #fff; font-family: 'DM Sans', sans-serif;
    font-size: .95rem; font-weight: 700; border: none;
    border-radius: 14px; cursor: pointer;
    transition: all .25s;
    display: flex; align-items: center; justify-content: center; gap: 9px;
    box-shadow: 0 6px 22px rgba(22,163,74,.36);
    position: relative; overflow: hidden;
}
.btn-reg::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to bottom right, rgba(255,255,255,.18), transparent);
    opacity: 0; transition: opacity .2s;
}
.btn-reg:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(22,163,74,.42); }
.btn-reg:hover::after { opacity: 1; }
.btn-reg:active { transform: translateY(1px); }
.btn-reg:disabled {
    background: linear-gradient(135deg, #94a3b8, #cbd5e1);
    box-shadow: none; cursor: not-allowed; transform: none;
}

/* Login link */
.login-link {
    text-align: center; margin-top: 18px;
    font-size: .88rem; color: var(--muted);
}
.login-link a { color: var(--green-deep); font-weight: 700; text-decoration: none; }
.login-link a:hover { text-decoration: underline; }

/* Animations */
@keyframes aUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ═══════════════════════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS (mobile-first)
   ═══════════════════════════════════════════════════════════════ */

@media (min-width: 480px) {
    .grid-2 {
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .form-box { max-width: 480px; }
}

@media (min-width: 640px) {
    .right-panel { padding: 40px 40px; align-items: center; }
    .form-head h1 { font-size: 1.85rem; }
    .role-box { padding: 16px 14px; }
    .role-emoji { font-size: 1.6rem; }
    .fi { font-size: .9rem; }
}

@media (min-width: 800px) {
    body { flex-direction: row; }
    .left-panel { display: flex; }
    .right-panel::before { display: block; }
}

@media (max-width: 799px) {
    body {
        flex-direction: column;
        background: #f8fafc;
        min-height: 100%;
        height: auto;
        overflow-y: auto;
    }
    .left-panel {
        width: 100%; min-height: auto;
        padding: 24px 20px 28px;
        border-radius: 0 0 28px 28px;
        justify-content: flex-start;
        flex-shrink: 0;
    }
    .orb-1 { width: 220px; height: 220px; top: -60px; right: -50px; }
    .orb-2 { width: 160px; height: 160px; bottom: -40px; left: -40px; }
    .steps-box, .stats-row { display: none; }
    .left-content { max-width: 100%; text-align: left; }
    .logo-badge { margin-bottom: 14px; }
    .left-h1 { font-size: clamp(1.2rem, 5vw, 1.5rem); }
    .left-p { font-size: .82rem; }
    .right-panel {
        padding: 20px 16px 52px;
        align-items: flex-start;
        overflow-y: visible;
    }
    .right-panel::before { display: none; }
    .form-box { padding: 20px 0 40px; }
    .form-head h1 { font-size: 1.45rem; }
}

@media (min-width: 480px) and (max-width: 799px) {
    .left-panel { padding: 28px 28px 32px; }
    .left-h1    { font-size: 1.4rem; }
    .steps-box  { display: block; }
    .stats-row  { display: none; }
    .left-p     { font-size: .85rem; }
    .right-panel { padding: 28px 28px 52px; }
}

@media (min-width: 1280px) {
    .left-panel { width: 44%; padding: 60px 52px; }
    .left-h1    { font-size: 2rem; }
    .stats-row  { display: flex; }
    .steps-box  { display: block; }
    .right-panel { padding: 40px 48px; }
    .form-box   { max-width: 500px; }
}

@media (min-width: 1024px) and (max-width: 1279px) {
    .left-panel { width: 42%; padding: 50px 40px; }
    .left-h1    { font-size: 1.75rem; }
    .steps-box  { display: block; }
    .stats-row  { display: flex; }
}

@media (min-width: 800px) and (max-width: 1023px) {
    .left-panel { width: 40%; padding: 40px 28px; }
    .left-h1    { font-size: 1.45rem; }
    .steps-box  { display: block; }
    .stats-row  { display: none; }
    .right-panel { padding: 28px 24px; }
    .form-box   { max-width: 420px; padding: 16px 0 40px; }
}
</style>
</head>
<body>

<!-- ══ LEFT PANEL ══════════════════════════════════════════════ -->
<div class="left-panel">
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <div class="left-content">
        <div class="logo-badge">
            <div class="logo-icon">KC</div>
            <span class="logo-name">KirimCepat</span>
        </div>

        <h1 class="left-h1">Bergabung &amp;<br><span>Mulai Perjalanan</span><br>Bersama Kami</h1>
        <p class="left-p">Ribuan customer dan mitra sudah bergabung. Giliran Anda menikmati kemudahan pengiriman modern.</p>

        <div class="steps-box">
            <div class="step-row">
                <div class="step-num">1</div>
                <div class="step-ico">📝</div>
                <div class="step-txt">
                    <h4>Isi Data Diri</h4>
                    <p>Lengkapi nama, email, dan nomor telepon Anda</p>
                </div>
            </div>
            <div class="step-row">
                <div class="step-num">2</div>
                <div class="step-ico">✅</div>
                <div class="step-txt">
                    <h4>Verifikasi Akun</h4>
                    <p>Konfirmasi email dan akun siap digunakan</p>
                </div>
            </div>
            <div class="step-row">
                <div class="step-num">3</div>
                <div class="step-ico">🚀</div>
                <div class="step-txt">
                    <h4>Mulai Memesan</h4>
                    <p>Kirim barang, antar orang, atau pesan makanan</p>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-box"><div class="stat-n">10K+</div><div class="stat-l">Customer Aktif</div></div>
            <div class="stat-box"><div class="stat-n">500+</div><div class="stat-l">Mitra Driver</div></div>
            <div class="stat-box"><div class="stat-n">4.9⭐</div><div class="stat-l">Rating Rata-rata</div></div>
        </div>
    </div>
</div>

<!-- ══ RIGHT PANEL ═════════════════════════════════════════════ -->
<div class="right-panel">
    <div class="form-box">

        <div class="form-head">
            <h1>Buat Akun Baru ✨</h1>
            <p>Bergabung sebagai Customer atau Mitra Driver</p>
        </div>

        @if($errors->any())
        <div class="alert-err">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="regForm">
            @csrf

            <!-- Role Selector -->
            <div class="role-wrap">
                <span class="role-label">Daftar Sebagai</span>
                <div class="role-grid">
                    <label class="role-card">
                        <input type="radio" name="role" value="customer" id="roleCustomer"
                               {{ old('role','customer') === 'customer' ? 'checked' : '' }}
                               onchange="toggleVehicleField()">
                        <div class="role-box">
                            <div class="role-check">✓</div>
                            <div class="role-emoji">🛒</div>
                            <div class="role-name">Customer</div>
                            <div class="role-desc">Kirim, antar &amp; pesan makanan</div>
                        </div>
                    </label>
                    <label class="role-card">
                        <input type="radio" name="role" value="mitra" id="roleMitra"
                               {{ old('role') === 'mitra' ? 'checked' : '' }}
                               onchange="toggleVehicleField()">
                        <div class="role-box">
                            <div class="role-check">✓</div>
                            <div class="role-emoji">🏍️</div>
                            <div class="role-name">Mitra Driver</div>
                            <div class="role-desc">Ambil, antar &amp; raih penghasilan</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- ── Vehicle Type (hanya tampil saat Mitra dipilih) ── -->
            <div class="vehicle-wrap {{ old('role') === 'mitra' ? 'show' : '' }}" id="vehicleWrap">
                <span class="role-label">Jenis Kendaraan</span>
                <div class="vehicle-grid">
                    <label class="vehicle-card">
                        <input type="radio" name="vehicle_type" value="motor"
                               id="vMotor"
                               {{ old('vehicle_type', 'motor') === 'motor' ? 'checked' : '' }}>
                        <div class="vehicle-box">
                            <div class="vehicle-check">✓</div>
                            <span class="vehicle-ico">🛵</span>
                            <span class="vehicle-name">Motor</span>
                        </div>
                    </label>
                    <label class="vehicle-card">
                        <input type="radio" name="vehicle_type" value="mobil"
                               id="vMobil"
                               {{ old('vehicle_type') === 'mobil' ? 'checked' : '' }}>
                        <div class="vehicle-box">
                            <div class="vehicle-check">✓</div>
                            <span class="vehicle-ico">🚗</span>
                            <span class="vehicle-name">Mobil</span>
                        </div>
                    </label>
                </div>
            </div>
            <!-- ─────────────────────────────────────────────────── -->

            <!-- Full name -->
            <div class="fg">
                <label class="fl" for="rName">Nama Lengkap</label>
                <div class="fw">
                    <span class="fi-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" id="rName" name="name" value="{{ old('name') }}" required
                           class="fi" placeholder="Nama lengkap sesuai KTP" autocomplete="name">
                </div>
            </div>

            <!-- Email + Phone -->
            <div class="grid-2">
                <div class="fg">
                    <label class="fl" for="rEmail">Email</label>
                    <div class="fw">
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <input type="email" id="rEmail" name="email" value="{{ old('email') }}" required
                               class="fi" placeholder="email@contoh.com" autocomplete="email">
                    </div>
                </div>
                <div class="fg">
                    <label class="fl" for="rPhone">No. Telepon</label>
                    <div class="fw">
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5 19.79 19.79 0 0 1 1.64 4.87 2 2 0 0 1 3.6 2.69h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.15 6.15l1.28-1.28a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input type="tel" id="rPhone" name="phone" value="{{ old('phone') }}" required
                               class="fi" placeholder="08xxxxxxxxxx" autocomplete="tel">
                    </div>
                </div>
            </div>

            <!-- Password + Confirm -->
            <div class="grid-2">
                <div class="fg">
                    <label class="fl" for="rPwd">Password</label>
                    <div class="fw">
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" id="rPwd" name="password" required minlength="8"
                               class="fi" placeholder="Min. 8 karakter" oninput="checkStrength(this.value)">
                        <button type="button" class="fi-eye" onclick="togglePwd('rPwd','eye1S','eye1H')">
                            <svg id="eye1S" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye1H" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <div class="pwd-strength">
                        <div class="strength-bar">
                            <div class="s-seg" id="s1"></div>
                            <div class="s-seg" id="s2"></div>
                            <div class="s-seg" id="s3"></div>
                            <div class="s-seg" id="s4"></div>
                        </div>
                        <div class="s-label" id="sLabel">Masukkan password</div>
                    </div>
                </div>
                <div class="fg">
                    <label class="fl" for="rPwd2">Konfirmasi Password</label>
                    <div class="fw">
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        <input type="password" id="rPwd2" name="password_confirmation" required
                               class="fi" placeholder="Ulangi password" oninput="matchCheck()">
                        <button type="button" class="fi-eye" onclick="togglePwd('rPwd2','eye2S','eye2H')">
                            <svg id="eye2S" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye2H" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    <div style="min-height:24px; padding-top:6px; font-size:.7rem" id="matchLabel"></div>
                </div>
            </div>

            <!-- Terms -->
            <div class="terms-row">
                <input type="checkbox" id="termsChk" class="terms-chk" required onchange="updateBtn()">
                <label for="termsChk">
                    Saya setuju dengan <a href="#">Syarat &amp; Ketentuan</a> dan
                    <a href="#">Kebijakan Privasi</a> KirimCepat
                </label>
            </div>

            <button type="submit" class="btn-reg" id="submitBtn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Buat Akun Sekarang
            </button>
        </form>

        <p class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini →</a>
        </p>
    </div>
</div>

<script>
// ── Toggle field kendaraan saat role berubah ─────────────────
function toggleVehicleField() {
    const isMitra = document.getElementById('roleMitra').checked;
    const wrap    = document.getElementById('vehicleWrap');
    const vMotor  = document.getElementById('vMotor');
    const vMobil  = document.getElementById('vMobil');

    if (isMitra) {
        wrap.classList.add('show');
        // Wajibkan salah satu dipilih
        vMotor.required = true;
        vMobil.required = true;
        // Default ke motor jika belum dipilih
        if (!vMotor.checked && !vMobil.checked) vMotor.checked = true;
    } else {
        wrap.classList.remove('show');
        // Tidak wajib jika customer
        vMotor.required = false;
        vMobil.required = false;
        vMotor.checked  = false;
        vMobil.checked  = false;
    }
}

// Jalankan saat halaman load (untuk handle old() Laravel setelah error)
document.addEventListener('DOMContentLoaded', function () {
    toggleVehicleField();
});

// ── Toggle show/hide password ────────────────────────────────
function togglePwd(inputId, showId, hideId) {
    const inp = document.getElementById(inputId);
    const isHide = inp.type === 'password';
    inp.type = isHide ? 'text' : 'password';
    document.getElementById(showId).style.display = isHide ? 'none' : '';
    document.getElementById(hideId).style.display = isHide ? '' : 'none';
}

// ── Password strength checker ────────────────────────────────
function checkStrength(val) {
    const segs = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const lbl  = document.getElementById('sLabel');
    segs.forEach(s => s.className = 's-seg');
    if (!val) { lbl.textContent = 'Masukkan password'; lbl.style.color = '#94a3b8'; return; }
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { cls:'weak',   label:'Lemah',  color:'#ef4444' },
        { cls:'ok',     label:'Cukup',  color:'#f59e0b' },
        { cls:'good',   label:'Baik',   color:'#22c55e' },
        { cls:'strong', label:'Kuat',   color:'#16a34a' },
    ];
    const lvl = levels[score - 1] || levels[0];
    segs.slice(0, score).forEach(s => s.classList.add(lvl.cls));
    lbl.textContent = lvl.label; lbl.style.color = lvl.color;
}

// ── Password match checker ───────────────────────────────────
function matchCheck() {
    const p1  = document.getElementById('rPwd').value;
    const p2  = document.getElementById('rPwd2').value;
    const lbl = document.getElementById('matchLabel');
    if (!p2) { lbl.textContent = ''; return; }
    lbl.textContent = p1 === p2 ? '✓ Password cocok' : '✕ Belum cocok';
    lbl.style.color = p1 === p2 ? '#16a34a' : '#ef4444';
}

// ── Enable/disable submit button ─────────────────────────────
function updateBtn() {
    document.getElementById('submitBtn').disabled = !document.getElementById('termsChk').checked;
}
</script>
</body>
</html>