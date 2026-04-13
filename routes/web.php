<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Mitra\MitraController;
use App\Http\Controllers\Mitra\MitraHistoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\PaymentController;

// ── Public ────────────────────────────────────────────────────
Route::get('/', fn() => view('landing'))->name('home');

// ── Auth ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Customer ──────────────────────────────────────────────────
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/order', [CustomerController::class, 'orderForm'])->name('order');
        Route::post('/order', [CustomerController::class, 'store'])->name('order.store');
        Route::get('/tracking', [CustomerController::class, 'tracking'])->name('tracking');
        Route::get('/order/{order}', [CustomerController::class, 'show'])->name('order.show');
        Route::post('/order/{order}/rate', [CustomerController::class, 'rate'])->name('order.rate');
        Route::post('/estimate-price', [CustomerController::class, 'estimatePrice'])->name('estimate');
        Route::post('/order/{order}/cancel', [CustomerController::class, 'cancelOrder'])->name('order.cancel');
        Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payment');
        Route::post('/orders/{order}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.upload');

        // Profile
        Route::post('/profile/photo',   [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
        Route::get('/profile', [ProfileController::class, 'customerProfile'])->name('profile');
        Route::put('/profile/info', [ProfileController::class, 'customerUpdateInfo'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'customerUpdatePassword'])->name('profile.password');
    });

// ── Mitra ─────────────────────────────────────────────────────
Route::prefix('mitra')
    ->name('mitra.')
    ->middleware(['auth', 'role:mitra'])
    ->group(function () {
        Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');
        Route::post('/toggle-online', [MitraController::class, 'toggleOnline'])->name('toggle-online');
        Route::patch('/order/{order}/update-status', [MitraController::class, 'updateStatus'])->name('order.update-status');
        Route::post('/order/{order}/accept', [MitraController::class, 'acceptOrder'])->name('order.accept');
        Route::post('/order/{order}/cancel', [MitraController::class, 'cancelOrder'])->name('order.cancel');
        Route::get('/earnings', [MitraController::class, 'earnings'])->name('earnings');
        Route::get('/profile', [MitraController::class, 'profile'])->name('profile');
        Route::get('/ratings', [MitraController::class, 'ratings'])->name('ratings');
        Route::post('/profile/photo',   [ProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');


        // Profile update
        Route::put('/profile', [ProfileController::class, 'mitraUpdateInfo'])->name('profile.update');
        Route::put('/profile/vehicle', [ProfileController::class, 'mitraUpdateVehicle'])->name('profile.vehicle');
        Route::put('/profile/documents', [ProfileController::class, 'mitraUpdateDocuments'])->name('profile.documents');
        Route::put('/profile/password', [ProfileController::class, 'mitraUpdatePassword'])->name('profile.password');

        // History
        Route::get('/history', [MitraHistoryController::class, 'index'])->name('history');
    });

// ── Admin ─────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Transaksi & Order
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/{order}', [AdminController::class, 'orderDetail'])->name('orders.detail');
        Route::patch('/transactions/{order}', [AdminController::class, 'forceUpdateStatus'])->name('order.update');

        // Pembayaran
        Route::post('/orders/{order}/confirm-payment', [AdminController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::post('/orders/{order}/reject-payment', [AdminController::class, 'rejectPayment'])->name('orders.reject-payment');
        Route::get('/pending-transfers', [AdminController::class, 'pendingTransfers'])->name('payments.transfers');

        // Customer
        Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
        Route::post('/customers/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('customers.toggle');

        // Mitra
        Route::get('/mitras', [AdminController::class, 'mitras'])->name('mitras');
        Route::post('/mitras/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('mitras.toggle');
        Route::post('/mitras/{user}/verify', [AdminController::class, 'verifyMitra'])->name('mitras.verify');
      // routes/web.php


        // Tarif
        Route::get('/rates', [AdminController::class, 'rates'])->name('rates');
        Route::post('/rates', [AdminController::class, 'updateRates'])->name('rates.update');

 // Bonus Performa
Route::get('/bonus', [AdminController::class, 'bonusIndex'])->name('bonus.index');
Route::put('/bonus/{rate}', [AdminController::class, 'bonusUpdate'])->name('bonus.update');
Route::post('/bonus/manual', [AdminController::class, 'bonusManual'])->name('bonus.manual');
Route::post('/bonus/process', [AdminController::class, 'processAll'])->name('bonus.process');


        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Profile
        
        Route::get('/profile', [ProfileController::class, 'adminProfile'])->name('profile');
        Route::put('/profile/info', [ProfileController::class, 'adminUpdateInfo'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'adminUpdatePassword'])->name('profile.password');
    });

// ── Chat (Customer ↔ Mitra) ───────────────────────────────────
Route::prefix('chat')
    ->name('chat.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/{order}/messages', [ChatController::class, 'messages'])->name('messages');
        Route::post('/{order}/send', [ChatController::class, 'send'])->name('send');
        Route::get('/{order}/unread', [ChatController::class, 'unread'])->name('unread');
        Route::get('/{order}/unread-count', [ChatController::class, 'unreadCount'])->name('unread.count');
        Route::get('/{order}/partner', [ChatController::class, 'partner'])->name('partner');
    });