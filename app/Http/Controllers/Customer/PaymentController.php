<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // ── Tampilkan halaman pembayaran ───────────────────────────
    public function show(Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');
        abort_if($order->customer_id !== auth()->id(), 403);

        // Pastikan order ini memang butuh halaman pembayaran digital
        // Cash tidak punya halaman payment
        abort_if($order->payment_method === 'cash', 404);

        return view('customer.payment', [
            'order' => $order,
            'qrisImage' => Setting::get('qris_image'),
            'qrisNote' => Setting::get('qris_note'),
            'bankInfo' => Setting::get('bank_info'),
        ]);
    }

    // ── Upload bukti pembayaran ────────────────────────────────
    public function uploadProof(Request $request, Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');

        abort_if($order->customer_id !== auth()->id(), 403);
        abort_if($order->isPaid(), 422, 'Pesanan sudah dikonfirmasi pembayarannya.');

        // Hanya transfer dan qris yang bisa upload bukti
        // Transfer, qris, dan e_wallet bisa upload bukti
        abort_if(!in_array($order->payment_method, ['transfer', 'qris', 'e_wallet']), 422, 'Metode pembayaran ini tidak memerlukan upload bukti.');

        $request->validate(
            [
                'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ],
            [
                'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
                'payment_proof.image' => 'File harus berupa gambar (JPG/PNG).',
                'payment_proof.mimes' => 'Format file harus JPG atau PNG.',
                'payment_proof.max' => 'Ukuran file maksimal 3MB.',
            ],
        );

        // Hapus bukti lama jika sudah ada
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'payment_status' => 'pending', // menunggu konfirmasi admin
        ]);

        $label = $order->payment_method === 'transfer' ? 'transfer' : 'QRIS';

        return back()->with('success', "Bukti pembayaran {$label} berhasil diupload! Admin akan segera memverifikasi.");
    }
}
