<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use App\Models\{Order, MitraProfile};

class ProfileController extends Controller
{
    // ── CUSTOMER ──────────────────────────────────────────────

    public function customerProfile()
    {
        $user = Auth::user();
        $totalOrders     = Order::where('customer_id', $user->id)->count();
        $completedOrders = Order::where('customer_id', $user->id)->where('status', 'completed')->count();
        $totalSpend      = Order::where('customer_id', $user->id)->where('status', 'completed')->sum('total_fare');
        $recentOrders    = Order::where('customer_id', $user->id)->latest()->take(10)->get();

        return view('customer.profile', compact('totalOrders', 'completedOrders', 'totalSpend', 'recentOrders'));
    }

    public function customerUpdateInfo(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        Auth::user()->update($data);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function customerUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah!');
    }

    // ── MITRA ──────────────────────────────────────────────────

    public function mitraProfile()
    {
        return view('mitra.profile');
    }

    public function mitraUpdateInfo(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($data);
        return back()->with('success', 'Informasi berhasil diperbarui!');
    }

    public function mitraUpdateVehicle(Request $request)
    {
        $data = $request->validate([
            'vehicle_type'  => 'required|in:motor,mobil',
            'vehicle_brand' => 'nullable|string|max:50',
            'vehicle_plate' => 'nullable|string|max:20',
        ]);

        MitraProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function mitraUpdateDocuments(Request $request)
    {
        $request->validate([
            'ktp_number' => 'nullable|digits:16',
            'sim_number' => 'nullable|string|max:20',
            'ktp_photo'  => 'nullable|image|max:2048',
            'sim_photo'  => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'ktp_number' => $request->ktp_number,
            'sim_number' => $request->sim_number,
        ];

        if ($request->hasFile('ktp_photo')) {
            $profile = MitraProfile::where('user_id', Auth::id())->first();
            if ($profile?->ktp_photo) Storage::delete($profile->ktp_photo);
            $updateData['ktp_photo'] = $request->file('ktp_photo')->store('mitra/ktp', 'public');
        }

        if ($request->hasFile('sim_photo')) {
            $profile = $profile ?? MitraProfile::where('user_id', Auth::id())->first();
            if ($profile?->sim_photo) Storage::delete($profile->sim_photo);
            $updateData['sim_photo'] = $request->file('sim_photo')->store('mitra/sim', 'public');
        }

        MitraProfile::updateOrCreate(['user_id' => Auth::id()], $updateData);
        return back()->with('success', 'Dokumen berhasil diupload!');
    }

    public function mitraUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah!');
    }

    // ── ADMIN ──────────────────────────────────────────────────

    public function adminProfile()
    {
        $todayOrders    = Order::whereDate('created_at', today())->count();
        $totalMitras    = \App\Models\User::where('role', 'mitra')->count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();

        return view('admin.profile', compact('todayOrders', 'totalMitras', 'totalCustomers'));
    }

    public function adminUpdateInfo(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($data);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function adminUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        Auth::logout();
        return redirect()->route('login')->with('success', 'Password diubah. Silakan login kembali.');
    }

    // ── UPLOAD FOTO PROFIL (untuk semua role) ──────────────────

public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'photo.required' => 'Pilih foto terlebih dahulu.',
        'photo.image'    => 'File harus berupa gambar.',
        'photo.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
        'photo.max'      => 'Ukuran foto maksimal 2MB.',
    ]);

    $user = Auth::user();

    // Hapus foto lama jika ada
    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
        Storage::disk('public')->delete($user->photo);
    }

    // Simpan ke storage/app/public/photos/{user_id}/
    $path = $request->file('photo')->store("photos/{$user->id}", 'public');

    $user->update(['photo' => $path]);

    return back()->with('success', 'Foto profil berhasil diperbarui!');
}

public function deletePhoto()
{
    $user = Auth::user();

    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
        Storage::disk('public')->delete($user->photo);
    }

    $user->update(['photo' => null]);

    return back()->with('success', 'Foto profil berhasil dihapus.');
}
}