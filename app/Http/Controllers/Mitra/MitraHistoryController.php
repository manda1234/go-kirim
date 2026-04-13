<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraHistoryController extends Controller
{
    public function index(Request $request)
    {
        $mitraId = Auth::id();

        // ── Base query: hanya order milik mitra yang login ──────────────
        $query = Order::where('mitra_id', $mitraId)
            ->whereIn('status', ['completed', 'cancelled', 'rejected'])
            ->with('customer:id,name')
            ->latest();

        // ── Filter status ────────────────────────────────────────────────
        if ($request->filled('status') && in_array($request->status, ['completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        // ── Search berdasarkan order_code ────────────────────────────────
        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%');
        }

        // ── Statistik ringkas ────────────────────────────────────────────
        $statsBase = Order::where('mitra_id', $mitraId)
            ->whereIn('status', ['completed', 'cancelled', 'rejected']);

        $stats = [
            'total_order'    => (clone $statsBase)->count(),
            'total_earning'  => (clone $statsBase)->where('status', 'completed')->sum('mitra_earning'),
            'total_cancel'   => (clone $statsBase)->whereIn('status', ['cancelled', 'rejected'])->count(),
        ];

        // ── Pagination ───────────────────────────────────────────────────
        $orders = $query->paginate(10)->withQueryString();

        return view('mitra.history', compact('orders', 'stats'));
    }
}