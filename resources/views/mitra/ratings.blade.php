{{-- resources/views/mitra/ratings.blade.php --}}
@extends('layouts.app')

@section('title', 'Rating & Ulasan Saya')

@section('sidebar-nav')
  <a href="{{ route('mitra.dashboard') }}" class="sidebar-link active">
    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
  </a>
  <a href="{{ route('mitra.earnings') }}" class="sidebar-link">
    <i class="fas fa-wallet w-5"></i> Pendapatan
  </a>
  <a href="{{ route('mitra.history') }}" class="sidebar-link">
    <i class="fas fa-history w-5"></i> Riwayat Pesanan
  </a>
  <a href="{{ route('mitra.ratings') }}" class="sidebar-link">
    <i class="fas fa-star w-5"></i> Rating & Ulasan
  </a>
@endsection


@push('styles')
<style>
    :root {
        --brand:      #f97316;
        --brand-soft: #fff7ed;
        --star-on:    #f59e0b;
        --star-off:   #e5e7eb;
        --text:       #111827;
        --muted:      #6b7280;
        --border:     #e5e7eb;
        --card:       #ffffff;
        --bg:         #f9fafb;
    }

    .rating-page { max-width: 820px; margin: auto; padding: 1.5rem 1rem 3rem; }

    /* Header */
    .page-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: .6rem;
        margin-bottom: 1.5rem;
    }
    .avg-badge {
        background: var(--brand);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        padding: .2rem .6rem;
        border-radius: 999px;
    }

    /* Stat cards */
    .stat-row {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 640px) {
        .stat-row { grid-template-columns: 1fr 1fr; }
        .stat-row .stat-dist { grid-column: span 2; }
    }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.1rem 1.3rem;
    }
    .stat-label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--muted);
        margin-bottom: .25rem;
    }
    .stat-value {
        font-size: 2.1rem;
        font-weight: 900;
        line-height: 1;
        color: var(--brand);
    }
    .stat-value.dark { color: var(--text); }

    /* Stars */
    .stars { color: var(--star-on); font-size: 1rem; letter-spacing: 1px; }
    .stars .off { color: var(--star-off); }

    /* Distribusi bintang */
    .dist-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .78rem;
        color: var(--muted);
        margin-bottom: .3rem;
    }
    .dist-row .label { width: 14px; color: var(--star-on); text-align: center; }
    .dist-bar {
        flex: 1;
        height: 7px;
        background: var(--star-off);
        border-radius: 999px;
        overflow: hidden;
    }
    .dist-fill {
        height: 100%;
        background: var(--star-on);
        border-radius: 999px;
        transition: width .5s ease;
    }
    .dist-count { width: 22px; text-align: right; }

    /* Review cards */
    .review-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.1rem 1.3rem;
        margin-bottom: .75rem;
        transition: box-shadow .2s;
    }
    .review-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.08); }

    .rc-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .5rem;
        flex-wrap: wrap;
        margin-bottom: .5rem;
    }
    .rc-left { display: flex; align-items: center; gap: .65rem; }
    .rc-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--brand-soft);
        border: 2px solid var(--brand);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
        color: var(--brand);
        font-size: .9rem;
        flex-shrink: 0;
    }
    .rc-name { font-weight: 700; font-size: .95rem; color: var(--text); }
    .rc-date { font-size: .75rem; color: var(--muted); }
    .rc-right { text-align: right; }
    .rc-code { font-size: .72rem; color: var(--muted); margin-top: .2rem; }

    .review-text {
        font-size: .88rem;
        color: #374151;
        line-height: 1.65;
        margin: 0;
    }
    .no-text { font-size: .82rem; color: var(--muted); font-style: italic; }

    /* Empty */
    .empty-box {
        text-align: center;
        padding: 3.5rem 1rem;
        color: var(--muted);
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
    }
    .empty-box .emoji { font-size: 2.8rem; margin-bottom: .75rem; }

    /* Section title */
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin: 1.5rem 0 .75rem;
    }
</style>
@endpush

@section('content')
<div class="rating-page">

    {{-- Judul --}}
    <div class="page-title">
        ⭐ Rating &amp; Ulasan Saya
        @if($stats->avg_rating)
            <span class="avg-badge">{{ $stats->avg_rating }} / 5.0</span>
        @endif
    </div>

    {{-- Stat cards --}}
    <div class="stat-row">

        {{-- Rata-rata --}}
        <div class="stat-card">
            <div class="stat-label">Rata-rata</div>
            <div class="stat-value">{{ $stats->avg_rating ?? '–' }}</div>
            @if($stats->avg_rating)
                @php $rounded = round($stats->avg_rating); @endphp
                <div class="stars mt-1" style="font-size:.9rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $rounded ? '' : 'off' }}">★</span>
                    @endfor
                </div>
            @endif
        </div>

        {{-- Total ulasan --}}
        <div class="stat-card">
            <div class="stat-label">Total Ulasan</div>
            <div class="stat-value dark">{{ $stats->total_reviews ?? 0 }}</div>
        </div>

        {{-- Distribusi bintang --}}
        <div class="stat-card stat-dist">
            <div class="stat-label">Distribusi Bintang</div>
            @php $total = max(1, $stats->total_reviews ?? 1); @endphp
            <div style="margin-top:.5rem;">
                @foreach([5,4,3,2,1] as $s)
                    @php
                        $key   = "star{$s}";
                        $count = (int)($stats->$key ?? 0);
                        $pct   = round($count / $total * 100);
                    @endphp
                    <div class="dist-row">
                        <span class="label">★</span>
                        <span style="width:10px;font-size:.75rem;">{{ $s }}</span>
                        <div class="dist-bar">
                            <div class="dist-fill" style="width:{{ $pct }}%;"></div>
                        </div>
                        <span class="dist-count">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Daftar ulasan --}}
    <div class="section-title">Ulasan Terbaru</div>

    @forelse($reviews as $order)
        <div class="review-card">
            <div class="rc-top">
                <div class="rc-left">
                    <div class="rc-avatar">
                        {{ strtoupper(substr($order->customer->name ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <div class="rc-name">{{ $order->customer->name ?? 'Customer' }}</div>
                        <div class="rc-date">
                            {{ \Carbon\Carbon::parse($order->completed_at)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>
                <div class="rc-right">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $order->customer_rating ? '' : 'off' }}">★</span>
                        @endfor
                    </div>
                    <div class="rc-code"># {{ $order->order_code }}</div>
                </div>
            </div>

            @if($order->customer_review)
                <p class="review-text">{{ $order->customer_review }}</p>
            @else
                <p class="no-text">Tidak ada komentar.</p>
            @endif
        </div>
    @empty
        <div class="empty-box">
            <div class="emoji">📭</div>
            <p class="fw-semibold mb-1">Belum ada ulasan</p>
            <small>Ulasan akan muncul setelah pesanan selesai dan customer memberikan rating.</small>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-3">{{ $reviews->links() }}</div>

</div>
@endsection