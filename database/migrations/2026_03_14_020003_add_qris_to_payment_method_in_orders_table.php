<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash','transfer','e_wallet','qris') NOT NULL");
}

public function down(): void
{
    DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash','transfer','e_wallet') NOT NULL");
}
};
