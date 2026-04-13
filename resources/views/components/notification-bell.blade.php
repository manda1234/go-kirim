@php
    use App\Models\OrderMessage;
    $user = auth()->user();
    $unreadCount = OrderMessage::whereHas('order', function ($q) use ($user) {
            $q->where('customer_id', $user->id)
              ->orWhere('mitra_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();
@endphp

<div class="relative" id="bellWrapper">
    <button
        id="bellBtn"
        onclick="toggleNotifDropdown()"
        class="relative p-2 rounded-full text-gray-500 hover:text-green-600 hover:bg-green-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        aria-label="Notifikasi pesan"
    >
        {{-- Ikon Lonceng --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Badge Merah --}}
        <span
            id="bellBadge"
            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 text-[10px] font-black
                   bg-red-500 text-white rounded-full flex items-center justify-center
                   border-2 border-white shadow-sm
                   transition-all duration-300
                   {{ $unreadCount > 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-0' }}"
        >
            {{ $unreadCount > 9 ? '9+' : ($unreadCount > 0 ? $unreadCount : '') }}
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div
        id="notifDropdown"
        class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
    >
        {{-- Header Dropdown --}}
        <div class="flex items-center justify-between px-4 py-3 bg-green-600">
            <span class="text-white font-bold text-sm">Notifikasi Pesan</span>
            <span id="dropdownBadge"
                  class="text-xs font-black bg-white text-green-700 rounded-full px-2 py-0.5
                         {{ $unreadCount > 0 ? '' : 'hidden' }}">
                {{ $unreadCount }} baru
            </span>
        </div>

        {{-- List Pesan --}}
        <div id="notifList" class="max-h-64 overflow-y-auto divide-y divide-gray-50">
            <div class="flex items-center justify-center py-8 text-gray-400 text-sm" id="notifLoading">
                <svg class="animate-spin w-4 h-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Memuat...
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50 text-center">
            <a href="{{ route('customer.orders.index') }}"
               class="text-xs font-semibold text-green-600 hover:text-green-700 hover:underline">
                Lihat semua pesanan →
            </a>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
let notifOpen = false;

function toggleNotifDropdown() {
    notifOpen = !notifOpen;
    const dropdown = document.getElementById('notifDropdown');

    if (notifOpen) {
        dropdown.classList.remove('hidden');
        loadNotifPreview();
    } else {
        dropdown.classList.add('hidden');
    }
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('bellWrapper');
    if (wrapper && !wrapper.contains(e.target) && notifOpen) {
        notifOpen = false;
        document.getElementById('notifDropdown').classList.add('hidden');
    }
});

// Load preview pesan unread di dropdown
function loadNotifPreview() {
    fetch('/chat/unread-preview', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const list = document.getElementById('notifList');

        if (!data.previews || data.previews.length === 0) {
            list.innerHTML = `
                <div class="flex flex-col items-center py-8 text-gray-400">
                    <svg class="w-10 h-10 mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                               6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                               6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0
                               11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="text-sm">Tidak ada pesan baru</span>
                </div>`;
            return;
        }

        list.innerHTML = data.previews.map(item => `
            <a href="/orders/${item.order_id}"
               class="flex items-start gap-3 px-4 py-3 hover:bg-green-50 transition-colors group">
                <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 font-black text-sm
                            flex items-center justify-center flex-shrink-0 group-hover:bg-green-200">
                    ${item.sender_name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-gray-800 truncate">${escapeHtml(item.sender_name)}</p>
                    <p class="text-xs text-gray-500 truncate mt-0.5">${escapeHtml(item.last_message)}</p>
                    <p class="text-[10px] text-gray-400 mt-1">Order #${item.order_id} · ${item.time}</p>
                </div>
                ${item.unread_count > 0 ? `
                <span class="flex-shrink-0 w-5 h-5 bg-red-500 text-white text-[10px] font-black
                             rounded-full flex items-center justify-center">
                    ${item.unread_count > 9 ? '9+' : item.unread_count}
                </span>` : ''}
            </a>
        `).join('');

        // Update badge count
        updateBellBadge(data.total_unread);
    })
    .catch(() => {
        document.getElementById('notifList').innerHTML =
            '<div class="text-center py-6 text-red-400 text-xs">Gagal memuat notifikasi</div>';
    });
}

// Auto-refresh badge setiap 30 detik
function refreshBadge() {
    fetch('/chat/unread-all', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => updateBellBadge(data.unread))
    .catch(() => {});
}

function updateBellBadge(count) {
    const badge = document.getElementById('bellBadge');
    const dropBadge = document.getElementById('dropdownBadge');

    if (count > 0) {
        badge.textContent = count > 9 ? '9+' : count;
        badge.classList.remove('opacity-0', 'scale-0');
        badge.classList.add('opacity-100', 'scale-100');
        dropBadge.textContent = count + ' baru';
        dropBadge.classList.remove('hidden');
    } else {
        badge.classList.add('opacity-0', 'scale-0');
        badge.classList.remove('opacity-100', 'scale-100');
        dropBadge.classList.add('hidden');
    }
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

// Start polling setiap 30 detik
setInterval(refreshBadge, 30000);
</script>
@endpush
@endonce