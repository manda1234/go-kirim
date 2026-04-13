<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Item makanan dalam food delivery
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('restaurant_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
        });

        // Histori status perjalanan order
        Schema::create('order_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'waiting','accepted','picking_up','in_progress',
                'delivered','completed','cancelled'
            ]);
            $table->string('description')->nullable();         // Pesan status
            $table->decimal('latitude', 10, 7)->nullable();   // Lokasi saat update
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
        });

        // Notifikasi
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('type', [
                'order_new', 'order_accepted', 'order_status',
                'order_completed', 'payment', 'promo', 'system'
            ])->default('system');
            $table->unsignedBigInteger('related_id')->nullable(); // order_id / dsb
            $table->string('related_type')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index('type');
        });

        // Alamat tersimpan customer
        Schema::create('saved_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label', 50);                      // Rumah, Kantor, dll
            $table->string('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('user_id');
        });

        // Pendapatan mitra per periode
        Schema::create('mitra_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('gross_amount', 10, 2);           // Total fare
            $table->decimal('platform_fee', 10, 2);           // Potongan platform
            $table->decimal('net_amount', 10, 2);             // Pendapatan bersih
            $table->decimal('bonus', 10, 2)->default(0);      // Bonus performa
            $table->date('earned_date');
            $table->boolean('is_disbursed')->default(false);  // Sudah dicairkan?
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamps();

            $table->index(['mitra_id', 'earned_date']);
            $table->index('is_disbursed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_earnings');
        Schema::dropIfExists('saved_addresses');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('order_trackings');
        Schema::dropIfExists('order_items');
    }
};