<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
       
        return view('admin.settings.index', [
            'qrisImage' => Setting::get('qris_image'),
            'bankInfo'  => Setting::get('bank_info', ''),
            'qrisNote'  => Setting::get('qris_note', ''),
        ]);
    }

    public function update(Request $request)
    {
        default_timezone_set('Asia/Jakarta');
        $request->validate([
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bank_info'  => 'nullable|string|max:500',
            'qris_note'  => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('qris_image')) {
            $old = Setting::get('qris_image');
            if ($old) Storage::disk('public')->delete($old);

            $path = $request->file('qris_image')->store('qris', 'public');
            Setting::set('qris_image', $path);
        }

        Setting::set('bank_info', $request->bank_info ?? '');
        Setting::set('qris_note', $request->qris_note ?? '');

        return back()->with('success', 'Pengaturan QRIS berhasil disimpan!');
    }

public function confirmPayment(Order $order)
{
    default_timezone_set('Asia/Jakarta');
    abort_if($order->isPaid(), 422, 'Pesanan sudah dikonfirmasi sebelumnya.');

    $order->update([
        'payment_status' => 'paid',
        'paid_at'        => now(),
        'status'         => 'completed',
        'completed_at'   => now(),
    ]);

    // Insert ke tabel mitra_earnings agar muncul di halaman earnings mitra
    if ($order->mitra_id) {
        \App\Models\MitraEarning::create([
            'mitra_id'     => $order->mitra_id,
            'order_id'     => $order->id,
            'gross_amount' => $order->total_fare,
            'platform_fee' => $order->platform_fee,
            'net_amount'   => $order->mitra_earning,
            'bonus'        => 0,
            'earned_date'  => now()->toDateString(),
            'is_disbursed' => false,
        ]);

        // Update total_earnings di mitra_profiles
        if ($order->mitra && $order->mitra->mitraProfile) {
            $order->mitra->mitraProfile->increment('total_earnings', $order->mitra_earning);
        }
    }

    return back()->with('success',
        'Pembayaran order #' . $order->order_code . ' berhasil dikonfirmasi!');
}
}