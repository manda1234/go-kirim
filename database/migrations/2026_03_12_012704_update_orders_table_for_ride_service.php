<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // ── 1. Tambah status baru untuk antar orang ──────────
            \DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
                'waiting',
                'accepted',
                'picking_up',
                'in_progress',
                'delivered',
                'completed',
                'cancelled',
                'on_the_way',
                'picked_up',
                'arrived'
            ) NOT NULL DEFAULT 'waiting'");

            // ── 2. Siapa yang membatalkan ─────────────────────────
            $table->string('cancelled_by')->nullable()
                  ->after('cancel_reason');
                  // nilai: 'customer' | 'mitra' | 'admin'

            // ── 3. Timestamp milestone ride ───────────────────────
            $table->timestamp('on_the_way_at')->nullable()
                  ->after('picked_up_at');  // driver menuju lokasi
            $table->timestamp('arrived_at')->nullable()
                  ->after('delivered_at');  // penumpang sampai tujuan
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Kembalikan enum status ke semula
            \DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
                'waiting',
                'accepted',
                'picking_up',
                'in_progress',
                'delivered',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'waiting'");

            $table->dropColumn([
                'cancelled_by',
                'on_the_way_at',
                'arrived_at',
            ]);
        });
    }
};