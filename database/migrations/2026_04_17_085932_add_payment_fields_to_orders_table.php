<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_payment_fields_to_orders_table.php

   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'payment_method')) {
            $table->string('payment_method')->default('cash');
        }
        if (!Schema::hasColumn('orders', 'bank_selected')) {
            $table->string('bank_selected')->nullable();
        }
        if (!Schema::hasColumn('orders', 'ewallet_selected')) {
            $table->string('ewallet_selected')->nullable();
        }
        if (!Schema::hasColumn('orders', 'payment_proof')) {
            $table->string('payment_proof')->nullable();
        }
        if (!Schema::hasColumn('orders', 'payment_status')) {
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
