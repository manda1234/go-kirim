<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah paid_by jika belum ada
            if (!Schema::hasColumn('orders', 'paid_by')) {
                $table->foreignId('paid_by')->nullable()
                      ->constrained('users')->onDelete('set null')
                      ->after('paid_at');
            }

            // Tambah cancelled_by jika belum ada
            if (!Schema::hasColumn('orders', 'cancelled_by')) {
                $table->string('cancelled_by')->nullable()->after('cancel_reason');
            }
        });

        // Ubah enum payment_status: ganti 'failed' dengan 'unpaid' dan 'refunded'
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('unpaid','pending','paid','refunded') NOT NULL DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['paid_by']);
            $table->dropColumn(['paid_by', 'cancelled_by']);
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending'");
    }
};