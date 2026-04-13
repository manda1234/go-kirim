<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk – KirimCepat</title>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --green: #22c55e;
    --green-deep: #16a34a;
    --green-dark: #14532d;
    --text: #0f172a;
    --muted: #64748b;
    --border: #e2e8f0;
}

html { height: 100%; }

body {
    font-family: 'DM Sans', sans-serif;
    background: #0d1a0f;
    display: flex;
    min-height: 100%;
    overflow-x: hidden;
}

/* ── Left panel ────────────────────────────────────────────── */
.left-panel {
    width: 52%;
    background: linear-gradient(145deg, #052e16 0%, #0d3321 30%, #14532d 60%, #166534 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 50px;
    /* decorative circles */
}
.left-panel::before {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 280px; height: 280px; border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}
.left-panel::after {
    content: '';
    position: absolute; bottom: -60px; left: -60px;
    width: 220px; height: 220px; border-radius: 50%;
    background: rgba(34,197,94,.07);
    pointer-events: none;
}

.left-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 400px;
    width: 100%;
}

/* ── Logo badge ────────────────────────────────────────────── */
.logo-badge {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 16px;
    padding: 10px 22px;
    margin-bottom: 44px;
}
.logo-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, #22c55e, #4ade80);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 13px;
    color: #052e16;
    flex-shrink: 0;
}
.logo-name {
    font-family: 'Syne', sans-serif;
    font-weight: 800; font-size: 1.2rem;
    color: #fff;
    white-space: nowrap;
}

/* ── Headline ──────────────────────────────────────────────── */
.left-h1 {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    color: #fff;
    line-height: 1.25;
    margin-bottom: 14px;
    /* font-size injected via responsive rules */
}
.left-h1 span { color: #86efac; }
.left-p {
    color: rgba(255,255,255,.6);
    font-size: .9rem;
    line-height: 1.65;
}

/* ── Pills ─────────────────────────────────────────────────── */
.pills {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-top: 28px;
}
.pill {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.11);
    border-radius: 30px;
    padding: 6px 14px;
    font-size: .78rem;
    color: #fff;
}

/* ── Right panel ───────────────────────────────────────────── */
.right-panel {
    flex: 1;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 36px;
    position: relative;
}

.form-box {
    width: 100%;
    max-width: 420px;
}

/* ── Form header ───────────────────────────────────────────── */
.form-head { margin-bottom: 30px; }
.form-head h1 {
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 6px;
    line-height: 1.2;
}
.form-head p { color: var(--muted); font-size: .9rem; }

/* ── Alert messages ────────────────────────────────────────── */
.msg-error {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 12px; padding: 12px;
    margin-bottom: 20px; color: #b91c1c; font-size: .85rem;
}
.msg-ok {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 12px; padding: 12px;
    margin-bottom: 20px; color: #15803d; font-size: .85rem;
}

/* ── Inputs ────────────────────────────────────────────────── */
.fgroup { margin-bottom: 16px; }
.flabel {
    display: block;
    font-size: .72rem; font-weight: 700;
    color: var(--muted);
    text-transform: uppercase; letter-spacing: .04em;
    margin-bottom: 8px;
}
.fwrap { position: relative; }
.finput {
    width: 100%;
    padding: 13px 46px;
    border: 2px solid var(--border);
    border-radius: 14px;
    font-size: .9rem;
    background: #fff;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.finput:focus {
    border-color: var(--green-deep);
    box-shadow: 0 0 0 4px rgba(22,163,74,.1);
}
.f-ico {
    position: absolute; left: 15px; top: 50%;
    transform: translateY(-50%);
    color: #b0bac8; pointer-events: none;
    font-style: normal;
}
.f-eye {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    padding: 4px;
}

/* ── Remember / forgot row ─────────────────────────────────── */
.row-check {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 22px;
    flex-wrap: wrap;
}
.chk-wrap {
    display: flex; align-items: center; gap: 8px;
    font-size: .85rem; color: var(--muted);
    cursor: pointer;
}
.chk-box { width: 18px; height: 18px; accent-color: var(--green-deep); }
.forgot-link {
    font-size: .85rem; color: var(--green-deep);
    text-decoration: none; white-space: nowrap;
}
.forgot-link:hover { text-decoration: underline; }

/* ── Submit button ─────────────────────────────────────────── */
.btn-go {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, #15803d, #22c55e);
    color: #fff; font-size: .95rem; font-weight: 700;
    border: none; border-radius: 14px;
    cursor: pointer;
    transition: opacity .2s, transform .15s;
}
.btn-go:hover { opacity: .92; }
.btn-go:active { transform: scale(.99); }

/* ── Demo section ──────────────────────────────────────────── */
.or-line {
    text-align: center;
    margin: 22px 0;
    font-size: .75rem; color: #94a3b8;
    display: flex; align-items: center; gap: 10px;
}
.or-line::before, .or-line::after {
    content: ''; flex: 1;
    height: 1px; background: var(--border);
}

.demo-wrap {
    background: #fff;
    border: 2px solid var(--border);
    border-radius: 18px;
    padding: 18px;
}
.demo-head {
    font-size: .7rem; font-weight: 800;
    color: #94a3b8; margin-bottom: 14px;
    text-transform: uppercase; letter-spacing: .05em;
}
.demo-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.demo-btn {
    padding: 12px 8px;
    border-radius: 14px;
    cursor: pointer;
    border: 1.5px solid var(--border);
    background: #fafafa;
    font-size: .78rem; font-weight: 600;
    color: var(--text);
    transition: background .15s, border-color .15s;
    text-align: center;
}
.demo-btn:hover { background: #f0fdf4; border-color: #86efac; }

/* ── Sign-up link ──────────────────────────────────────────── */
.sign-up {
    text-align: center;
    margin-top: 22px;
    font-size: .88rem;
    color: var(--muted);
}
.sign-up a { color: var(--green-deep); font-weight: 700; text-decoration: none; }
.sign-up a:hover { text-decoration: underline; }


/* ════════════════════════════════════════════════════════════
   RESPONSIVE BREAKPOINTS
   ════════════════════════════════════════════════════════════ */

/* ── Large desktop (1280px+) ────────────────────────────────── */
@media (min-width: 1280px) {
    .left-h1  { font-size: 2.4rem; }
    .left-panel { padding: 70px 60px; }
}

/* ── Desktop default (1024px – 1279px) ─────────────────────── */
@media (min-width: 1024px) and (max-width: 1279px) {
    .left-h1  { font-size: 2rem; }
}

/* ── Tablet landscape (768px – 1023px) ─────────────────────── */
@media (min-width: 768px) and (max-width: 1023px) {
    .left-panel { width: 46%; padding: 36px 28px; }
    .left-h1    { font-size: 1.55rem; }
    .pills      { gap: 6px; }
    .pill       { font-size: .72rem; padding: 5px 11px; }
    .logo-badge { margin-bottom: 32px; }
    .right-panel { padding: 32px 24px; }
    .form-box   { max-width: 360px; }
}

/* ── Mobile (< 768px): stack vertically ────────────────────── */
@media (max-width: 767px) {
    body {
        flex-direction: column;
        background: #f8fafc;
        min-height: 100%;
        height: auto;
        overflow-y: auto;
    }

    /* Left panel becomes a compact hero banner */
    .left-panel {
        width: 100%;
        padding: 28px 20px 32px;
        border-radius: 0 0 28px 28px;
        /* left-panel di mobile tidak perlu fixed height, ikut konten */
        flex-shrink: 0;
    }
    .left-panel::before { width: 160px; height: 160px; top: -50px; right: -50px; }
    .left-panel::after  { width: 120px; height: 120px; bottom: -40px; left: -40px; }

    .logo-badge { margin-bottom: 24px; padding: 8px 16px; }
    .logo-name  { font-size: 1rem; }

    .left-h1 { font-size: 1.45rem; }
    .left-p  { font-size: .84rem; }

    /* Hide pills on very small viewport to save space */
    .pills { display: none; }

    /* Right panel fills remaining space, scroll ikut body */
    .right-panel {
        padding: 28px 16px 48px;
        align-items: flex-start;
        overflow-y: visible;
    }
    .form-box    { max-width: 100%; }
    .form-head h1 { font-size: 1.5rem; }

    /* Demo grid: 2 cols on medium-mobile */
    .demo-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ── Small mobile (< 480px) ─────────────────────────────────── */
@media (max-width: 479px) {
    .left-panel   { padding: 24px 16px 28px; border-radius: 0 0 22px 22px; }
    .left-h1      { font-size: 1.25rem; }
    .left-p       { font-size: .8rem; }

    .right-panel  { padding: 22px 14px 44px; }
    .form-head h1 { font-size: 1.3rem; }

    .row-check    { flex-direction: column; align-items: flex-start; }

    /* Demo: 1 col with icon+label row layout */
    .demo-grid  { grid-template-columns: 1fr; }
    .demo-btn   { display: flex; align-items: center; gap: 10px; padding: 12px 14px; text-align: left; }

    .finput { font-size: .85rem; padding: 12px 42px; }
    .btn-go { padding: 13px; font-size: .9rem; }
}
</style>
</head>

<body>

{{-- ═══ LEFT PANEL ═══════════════════════════════════════════════ --}}
<div class="left-panel">
    <div class="left-content">

        <div class="logo-badge">
            <div class="logo-icon">KC</div>
            <span class="logo-name">KirimCepat</span>
        </div>

        <h1 class="left-h1">
            Satu Platform,<br>
            <span>Semua Kebutuhan</span><br>
            Pengirimanmu
        </h1>

        <p class="left-p">
            Kirim barang, antar orang, dan pesan makanan favorit —
            cepat, mudah, harga transparan.
        </p>

        <div class="pills">
            <div class="pill">📦 Kirim Barang</div>
            <div class="pill">🛵 Antar Orang</div>
            <div class="pill">📍 Tracking Realtime</div>
        </div>

    </div>
</div>

{{-- ═══ RIGHT PANEL ════════════════════════════════════════════════ --}}
<div class="right-panel">
    <div class="form-box">

        <div class="form-head">
            <h1>Selamat Datang 👋</h1>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        @if($errors->any())
        <div class="msg-error">{{ $errors->first() }}</div>
        @endif

        @if(session('success'))
        <div class="msg-ok">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="fgroup">
                <label class="flabel">Alamat Email</label>
                <div class="fwrap">
                    <span class="f-ico">📧</span>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required class="finput" placeholder="nama@email.com">
                </div>
            </div>

            <div class="fgroup">
                <label class="flabel">Password</label>
                <div class="fwrap">
                    <span class="f-ico">🔒</span>
                    <input type="password" name="password" id="loginPwd"
                           required class="finput" placeholder="Masukkan password">
                    <button type="button" class="f-eye" onclick="togglePwd()">👁</button>
                </div>
            </div>

            <div class="row-check">
                <label class="chk-wrap">
                    <input type="checkbox" name="remember" class="chk-box">
                    Ingat saya
                </label>
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="btn-go">Masuk Sekarang</button>
        </form>

        <div class="or-line">ATAU COBA DEMO</div>

        <div class="demo-wrap">
            <div class="demo-head">Login Demo</div>
            <div class="demo-grid">
                <button class="demo-btn" onclick="fillDemo('customer@demo.com')">
                    🧑 Customer
                </button>
                <button class="demo-btn" onclick="fillDemo('mitra@demo.com')">
                    🛵 Mitra
                </button>
                <button class="demo-btn" onclick="fillDemo('admin@demo.com')">
                    🔧 Admin
                </button>
            </div>
        </div>

        <p class="sign-up">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis →</a>
        </p>

    </div>
</div>

<script>
function togglePwd() {
    const inp = document.getElementById('loginPwd');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
function fillDemo(email) {
    document.querySelector('[name=email]').value = email;
    document.querySelector('[name=password]').value = 'password';
}
</script>

</body>
</html>