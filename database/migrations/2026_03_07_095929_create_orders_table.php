<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 20)->unique();          // KC-2024-0001
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mitra_id')->nullable()->constrained('users')->onDelete('set null');

            // Jenis layanan
            $table->enum('service_type', ['kirim_barang', 'antar_orang', 'food_delivery']);
            $table->enum('vehicle_type', ['motor', 'mobil'])->default('motor');

            // Lokasi pickup
            $table->string('pickup_address');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();

            // Lokasi tujuan
            $table->string('destination_address');
            $table->decimal('destination_lat', 10, 7)->nullable();
            $table->decimal('destination_lng', 10, 7)->nullable();

            // Detail barang (untuk kirim_barang)
            $table->enum('item_type', ['dokumen','elektronik','makanan','pakaian','obat','lainnya'])->nullable();
            $table->decimal('item_weight', 8, 2)->nullable();    // kg
            $table->text('notes')->nullable();

            // Harga
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->decimal('distance_fare', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(2000);
            $table->decimal('total_fare', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);  // Fee yang dipotong platform
            $table->decimal('mitra_earning', 10, 2)->default(0); // Pendapatan bersih mitra

            // Status
            $table->enum('status', [
                'waiting',      // Menunggu mitra
                'accepted',     // Diterima mitra
                'picking_up',   // Mitra menuju pickup
                'in_progress',  // Dalam perjalanan
                'delivered',    // Sudah diantar
                'completed',    // Selesai & dikonfirmasi customer
                'cancelled',    // Dibatalkan
            ])->default('waiting');

            $table->enum('payment_method', ['cash', 'transfer', 'e_wallet'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');

            // Rating
            $table->tinyInteger('customer_rating')->nullable();  // 1-5
            $table->text('customer_review')->nullable();

            // Timestamps milestone
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['mitra_id', 'status']);
            $table->index(['service_type', 'status']);
            $table->index('order_code');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};