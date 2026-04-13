{{--
    Chat Component
    Usage: @include('components.chat-box', ['order' => $order, 'authUser' => auth()->user()])
--}}
@push('styles')
    <style>
        #chatBox {
            position: fixed;
            bottom: 80px;
            right: 16px;
            width: 340px;
            max-width: calc(100vw - 32px);
            z-index: 999;
            display: none;
            flex-direction: column;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
            background: white;
            border: 1px solid #e2e8f0;
            max-height: 480px;
        }

        #chatBox.open {
            display: flex;
        }

        #chatToggleBtn {
            position: fixed;
            bottom: 80px;
            right: 16px;
            z-index: 1000;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #16a34a;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 20px rgba(22, 163, 74, 0.4);
            transition: transform 0.2s;
        }

        #chatToggleBtn:hover {
            transform: scale(1.08);
        }

        #chatBadge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid white;
        }

        #chatBadge.show {
            display: flex;
        }

        #chatMessages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 200px;
            max-height: 320px;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 16px;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
        }

        .chat-bubble.mine {
            align-self: flex-end;
            background: #16a34a;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-bubble.theirs {
            align-self: flex-start;
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }

        .chat-time {
            font-size: 10px;
            opacity: 0.6;
            margin-top: 3px;
            text-align: right;
        }

        .chat-bubble.theirs .chat-time {
            text-align: left;
        }

        .chat-name {
            font-size: 10px;
            font-weight: 700;
            opacity: 0.7;
            margin-bottom: 2px;
        }
    </style>
@endpush

{{-- Toggle Button --}}
<button id="chatToggleBtn" onclick="toggleChat()" title="Chat">
    <i class="fas fa-comment-dots" id="chatIcon"></i>
    <span id="chatBadge"></span>
</button>

{{-- Chat Box --}}
<div id="chatBox">
    {{-- Header --}}
    <div style="background:#16a34a;padding:12px 16px;display:flex;align-items:center;gap:10px;">
        <div
            style="width:36px;height:36px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.2);">
            @if (!empty($chatPartnerPhoto))
                <img src="{{ url('storage/' . $chatPartnerPhoto) }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <span style="font-weight:900;color:white;font-size:14px;">
                    {{ strtoupper(substr($chatPartnerName ?? 'M', 0, 1)) }}
                </span>
            @endif
        </div>
        <div style="flex:1;">
            <p style="font-weight:800;color:white;font-size:14px;margin:0;">{{ $chatPartnerName ?? 'Mitra' }}</p>
            <p style="font-size:11px;color:rgba(255,255,255,0.75);margin:0;" id="chatStatus">● Online</p>
        </div>
        <button onclick="toggleChat()"
            style="background:none;border:none;color:rgba(255,255,255,0.8);cursor:pointer;font-size:18px;padding:4px;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Messages --}}
    <div id="chatMessages">
        <div style="text-align:center;color:#94a3b8;font-size:12px;padding:8px 0;" id="chatLoading">
            <i class="fas fa-spinner fa-spin"></i> Memuat pesan...
        </div>
    </div>

    {{-- Input --}}
    <div style="padding:10px;border-top:1px solid #e2e8f0;display:flex;gap:8px;background:white;">
        <input type="text" id="chatInput" placeholder="Ketik pesan..." maxlength="500"
            style="flex:1;border:1.5px solid #e2e8f0;border-radius:12px;padding:8px 12px;font-size:13px;outline:none;"
            onkeydown="if(event.key==='Enter')sendMessage()" onfocus="this.style.borderColor='#16a34a'"
            onblur="this.style.borderColor='#e2e8f0'">
        <button onclick="sendMessage()"
            style="width:40px;height:40px;background:#16a34a;color:white;border:none;border-radius:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-paper-plane" style="font-size:14px;"></i>
        </button>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
const CHAT_ORDER_ID  = {{ $order->id }};
const CHAT_AUTH_ID   = {{ auth()->id() }};
const PUSHER_KEY     = '50f762dbbea60782d00e';
const PUSHER_CLUSTER = 'ap1';

let chatOpen    = false;
let unreadCount = 0;
let lastMsgId   = 0;
let pusher, channel;

// ── Pusher Init ────────────────────────────────────────────────
function initPusher() {
    pusher = new Pusher(PUSHER_KEY, {
        cluster: PUSHER_CLUSTER,
        forceTLS: true,
    });

    channel = pusher.subscribe('order-chat.' + CHAT_ORDER_ID);

    channel.bind('new-message', function(data) {
        if (data.sender_id !== CHAT_AUTH_ID) {
            appendMessage(data, false);
            if (!chatOpen) {
                unreadCount++;
                updateBadge();
            }
        }
    });

    channel.bind('pusher:subscription_succeeded', function() {
        document.getElementById('chatStatus').textContent = '● Terhubung';
    });
}

// ── Ambil data partner (FIX FINAL) ─────────────────────────────
function loadPartner() {
    fetch('/chat/' + CHAT_ORDER_ID + '/partner')
    .then(res => res.json())
    .then(data => {

        // target elemen yang BENAR
        const nameEl   = document.querySelector('#chatBox .chat-header-name');
        const avatarEl = document.querySelector('#chatBox .chat-avatar');

        // set nama
        if (nameEl) {
            nameEl.textContent = data.name;
        }

        // set foto / fallback huruf
        if (avatarEl) {
            if (data.photo) {
                avatarEl.innerHTML = `
                    <img src="${data.photo}" 
                         style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                `;
            } else {
                avatarEl.innerHTML = `
                    <span style="font-weight:900;color:white;font-size:14px;">
                        ${data.name.charAt(0).toUpperCase()}
                    </span>
                `;
            }
        }
    })
    .catch(() => {
        console.log('Gagal load partner');
    });
}

// ── Toggle Chat ────────────────────────────────────────────────
function toggleChat() {
    chatOpen = !chatOpen;
    const box = document.getElementById('chatBox');

    if (chatOpen) {
        box.classList.add('open');
        document.getElementById('chatToggleBtn').style.bottom = '500px';

        unreadCount = 0;
        updateBadge();

        loadMessages();
        loadPartner(); // ✅ WAJIB
    } else {
        box.classList.remove('open');
        document.getElementById('chatToggleBtn').style.bottom = '80px';
    }
}

// ── Load Messages ──────────────────────────────────────────────
function loadMessages() {
    fetch('/chat/' + CHAT_ORDER_ID + '/messages', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(messages => {
        const container = document.getElementById('chatMessages');
        container.innerHTML = '';

        if (messages.length === 0) {
            container.innerHTML =
                '<div style="text-align:center;color:#94a3b8;font-size:12px;padding:20px 0;">Belum ada pesan. Mulai chat!</div>';
            return;
        }

        messages.forEach(m => appendMessage(m, m.is_mine));
        if (messages.length > 0) lastMsgId = messages[messages.length - 1].id;
        scrollBottom();
    })
    .catch(() => {
        document.getElementById('chatMessages').innerHTML =
            '<div style="text-align:center;color:#ef4444;font-size:12px;padding:20px 0;">Gagal memuat pesan.</div>';
    });
}

// ── Append Message ─────────────────────────────────────────────
function appendMessage(data, isMine) {
    const container = document.getElementById('chatMessages');

    const empty = container.querySelector('[data-empty]');
    if (empty) empty.remove();

    const wrap = document.createElement('div');
    wrap.style.display = 'flex';
    wrap.style.flexDirection = 'column';
    wrap.style.alignItems = isMine ? 'flex-end' : 'flex-start';

    const nameEl = isMine ? '' : `<div class="chat-name">${data.sender_name}</div>`;

    wrap.innerHTML = `
        ${nameEl}
        <div class="chat-bubble ${isMine ? 'mine' : 'theirs'}">
            ${escapeHtml(data.message)}
            <div class="chat-time">${data.time}</div>
        </div>
    `;
    container.appendChild(wrap);
    scrollBottom();
}

// ── Send Message ───────────────────────────────────────────────
function sendMessage() {
    const input = document.getElementById('chatInput');
    const msg   = input.value.trim();
    if (!msg) return;

    input.value = '';
    input.disabled = true;

    fetch('/chat/' + CHAT_ORDER_ID + '/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                         || '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ message: msg }),
    })
    .then(r => r.json())
    .then(data => {
        appendMessage(data, true);
        input.disabled = false;
        input.focus();
    })
    .catch(() => {
        input.disabled = false;
        input.value = msg;
        alert('Gagal mengirim pesan. Coba lagi.');
    });
}

// ── Helpers ────────────────────────────────────────────────────
function scrollBottom() {
    const el = document.getElementById('chatMessages');
    el.scrollTop = el.scrollHeight;
}

function updateBadge() {
    const badge = document.getElementById('chatBadge');
    if (unreadCount > 0) {
        badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
        badge.classList.add('show');
    } else {
        badge.classList.remove('show');
    }
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

// ── Init ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    initPusher();
});
</script>
@endpush