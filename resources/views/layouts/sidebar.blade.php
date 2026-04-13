<aside class="hidden lg:flex flex-col w-64 bg-white border-r border-slate-100 shadow-sm h-screen sticky top-0">

    <!-- LOGO -->
    <div class="px-6 py-5 border-b border-slate-100">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm"
                style="background:#16a34a">KC</div>
            <span class="font-black text-lg text-slate-800">KirimCepat</span>
        </a>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @yield('sidebar-nav')
    </nav>

    <!-- USER + LOGOUT -->
    <div class="px-4 py-4 border-t border-slate-100">
        @php
            $profileRoute = match (auth()->user()->role) {
                'customer' => route('customer.profile'),
                'mitra' => route('mitra.profile'),
                'admin' => route('admin.profile'),
                default => '#',
            };
            $isProfileActive = request()->routeIs('*.profile*');
        @endphp

        <a href="{{ $profileRoute }}"
            class="flex items-center gap-3 w-full rounded-xl px-2 py-2.5 mb-2 transition-all
                   {{ $isProfileActive ? 'bg-green-50 ring-1 ring-green-100' : 'hover:bg-slate-50' }}">
            <div class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center flex-shrink-0">
                @if (auth()->user()->photo)
                    <img src="{{ url('storage/' . auth()->user()->photo) . '?v=' . time() }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center font-black text-sm"
                        style="background:#dcfce7; color:#16a34a">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate {{ $isProfileActive ? 'text-green-700' : 'text-slate-800' }}">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs {{ $isProfileActive ? 'text-green-500' : 'text-slate-400' }}">
                    <span class="capitalize">{{ auth()->user()->role }}</span>
                    <span class="mx-1">·</span>
                    <span>Lihat profil</span>
                </p>
            </div>
            <i class="fas fa-chevron-right text-xs {{ $isProfileActive ? 'text-green-500' : 'text-slate-300' }}"></i>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl
                           border border-slate-200 text-slate-500 text-sm font-semibold
                           hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition-all">
                <i class="fas fa-sign-out-alt text-xs"></i> Keluar
            </button>
        </form>
    </div>

</aside>
