<!DOCTYPE html>
<html lang="id" class="h-full">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KirimCepat') – Solusi Kirim & Antar</title>

    <!-- CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
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
        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .btn-primary {
            background-color: #16a34a;
            color: white;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #15803d;
        }

        .btn-primary:active {
            transform: scale(0.95);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
            background: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            background: white;
            outline: none;
            transition: border-color 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-select:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            white-space: nowrap;
        }

        .badge-info {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-warning {
            background: #fef9c3;
            color: #a16207;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-gray {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-purple {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .badge-orange {
            background: #ffedd5;
            color: #c2410c;
        }

        /* Order status */
        .badge-waiting {
            background: #fef9c3;
            color: #a16207;
        }

        .badge-accepted {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-picking_up {
            background: #f3e8ff;
            color: #7e22ce;
        }

        .badge-in_progress {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-completed {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* SIDEBAR LINK */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background: #f0fdf4;
            color: #16a34a;
        }

        .sidebar-link.active {
            background: #16a34a;
            color: white;
        }

        /* BOTTOM NAV */
        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 0;
            gap: 3px;
            flex: 1;
            color: #94a3b8;
            transition: color 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .bnav-item.active {
            color: #16a34a;
        }

        .bnav-item:hover {
            color: #16a34a;
        }

        .bnav-item span {
            font-size: 10px;
            font-weight: 700;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 99px;
        }

        /* TOAST */
        .kc-toast {
            position: fixed;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            white-space: nowrap;
            animation: kcFadeUp 0.3s ease;
        }

        @keyframes kcFadeUp {
            from {
                opacity: 0;
                transform: translate(-50%, 10px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

/* Popup */
.swal-popup-custom {
  border-radius: 24px !important;
  padding: 28px 20px 20px !important;
  width: 92% !important;
  max-width: 360px !important;
  font-family: inherit !important;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
}

/* Sembunyikan icon default SweetAlert */
.swal-icon-custom {
  border: none !important;
  margin-bottom: 0 !important;
}
.swal-icon-custom .swal2-icon-content { font-size: unset !important; }

/* Tombol aksi */
.swal-actions-custom {
  flex-direction: column !important;
  gap: 10px !important;
  width: 100% !important;
  padding: 0 !important;
  margin-top: 20px !important;
}

.swal-confirm-custom {
  width: 100% !important;
  background: #15803d !important;
  border-radius: 14px !important;
  padding: 14px !important;
  font-size: 14px !important;
  font-weight: 700 !important;
  box-shadow: none !important;
  transition: background 0.15s, transform 0.1s !important;
  letter-spacing: 0.01em !important;
}
.swal-confirm-custom:hover { background: #166534 !important; }
.swal-confirm-custom:active { transform: scale(0.98) !important; }

.swal-cancel-custom {
  width: 100% !important;
  background: #f1f5f9 !important;
  color: #475569 !important;
  border-radius: 14px !important;
  padding: 12px !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  box-shadow: none !important;
}
.swal-cancel-custom:hover { background: #e2e8f0 !important; }

/* Animasi masuk/keluar */
@keyframes swalSlideUp {
  from { opacity: 0; transform: translateY(40px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes swalSlideDown {
  from { opacity: 1; transform: translateY(0) scale(1); }
  to   { opacity: 0; transform: translateY(40px) scale(0.97); }
}
.swal-slide-up  { animation: swalSlideUp  0.3s cubic-bezier(.32,.72,0,1) forwards; }
.swal-slide-down{ animation: swalSlideDown 0.2s ease forwards; }



    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 min-h-screen">
    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col">

            {{-- Mobile Header --}}
            <header
                class="lg:hidden bg-white border-b border-slate-100 px-4 py-3
               flex items-center justify-between sticky top-0 z-40">

                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-black text-xs"
                        style="background:#16a34a">KC</div>
                    <span class="font-black text-base text-slate-800">KirimCepat</span>
                </a>

                <div class="flex items-center gap-2">

                  

                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center border-2"
                        style="background:#dcfce7;border-color:#bbf7d0">
                        <span class="text-xs font-black" style="color:#16a34a">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>

                </div>
            </header>


            

            {{-- Main --}}
            <main class="flex-1 overflow-y-auto pb-24 lg:pb-10">
                <div class="w-full px-4 lg:px-8">

                    @if (session('success'))
                        <div class="mt-4 p-4 rounded-xl text-sm font-semibold flex items-center gap-2"
                            style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d">
                            <i class="fas fa-check-circle" style="color:#22c55e"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mt-4 p-4 rounded-xl text-sm font-semibold flex items-center gap-2"
                            style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c">
                            <i class="fas fa-exclamation-circle" style="color:#ef4444"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mt-4 p-4 rounded-xl text-sm font-semibold flex items-center gap-2"
                            style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8">
                            <i class="fas fa-info-circle" style="color:#3b82f6"></i>
                            {{ session('info') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 p-4 rounded-xl text-sm font-semibold"
                            style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-exclamation-circle" style="color:#ef4444"></i>
                                <span>Terjadi kesalahan:</span>
                            </div>
                            @foreach ($errors->all() as $error)
                                <div class="ml-5 text-xs mt-0.5">• {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="py-6">
                        @yield('content')
                    </div>

                </div>
            </main>

            {{-- Mobile Bottom Nav --}}
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 flex z-50 shadow-lg">
                @yield('bottom-nav')
            </nav>

        </div>
    </div>

    {{-- GLOBAL JS HELPERS --}}
    <script>
        /**
         * POST JSON dengan CSRF token
         */
        async function postJson(url, data = {}) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    throw new Error(err.error || err.message || 'Terjadi kesalahan.');
                }
                return await res.json();
            } catch (e) {
                console.error('postJson error:', e);
                throw e;
            }
        }

        /**
         * Tampilkan toast notifikasi
         * @param {string} msg
         * @param {'success'|'error'|'warning'|'info'} type
         */
        function showToast(msg, type = 'success') {
            const colors = {
                success: '#16a34a',
                error: '#dc2626',
                warning: '#d97706',
                info: '#2563eb',
            };
            document.querySelectorAll('.kc-toast').forEach(t => t.remove());
            const el = document.createElement('div');
            el.className = 'kc-toast';
            el.style.background = colors[type] ?? colors.success;
            el.innerHTML = msg;
            document.body.appendChild(el);
            setTimeout(() => {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }, 3000);
        }

        /**
         * Format angka ke Rupiah
         */
        function rupiah(n) {
            return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
        }
    </script>

<script>
    /**
     * Konfigurasi Default SweetAlert untuk Konsistensi UI
     */
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    /**
     * OVERRIDE window.confirm -> Modern Premium Version
     */
    window.confirm = function(message, title = 'Konfirmasi') {
    return new Promise((resolve) => {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true, 
            
            // Matikan style bawaan agar tidak konflik
            buttonsStyling: false,
            
            customClass: {
                popup: 'rounded-3xl p-6 shadow-2xl border-none', // Lebih bulat & padding pas
                title: 'text-xl font-bold text-slate-800 pt-4',
                htmlContainer: 'text-slate-500 text-sm my-4',
                
                // Wrapper tombol untuk memastikan posisi sejajar
                actions: 'flex items-center justify-center gap-3 w-full mt-4',
                
                // Gunakan class Tailwind secara eksplisit
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-all active:scale-95 order-2',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold py-3 px-6 rounded-xl transition-all order-1'
            },
            
            // Memastikan lebar popup proporsional di mobile & desktop
            width: window.innerWidth < 640 ? '90%' : '400px',
            
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            }
        }).then((result) => {
            resolve(result.isConfirmed);
        });
    });
};
    /**
     * AUTO-HANDLE Form dengan onsubmit="return confirm(...)"
     */
    document.addEventListener('submit', function(e) {
        const form = e.target;
        // Cek apakah ada atribut onsubmit yang mengandung 'confirm'
        if (form.hasAttribute('onsubmit') && form.getAttribute('onsubmit').includes('confirm')) {
            e.preventDefault();

            const messageMatch = form.getAttribute('onsubmit').match(/confirm\(['"](.+?)['"]\)/);
            const message = messageMatch ? messageMatch[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

            window.confirm(message).then((ok) => {
                if (ok) {
                    // Hapus onsubmit agar tidak looping, lalu submit
                    form.removeAttribute('onsubmit');
                    form.submit();
                }
            });
        }
    });
</script>

    @stack('scripts')
</body>

</html>
