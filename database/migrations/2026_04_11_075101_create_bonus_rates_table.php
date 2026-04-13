<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/xxxx_create_bonus_rates_table.php
        Schema::create('bonus_rates', function (Blueprint $table) {
            $table->id();
            $table->string('tier_name'); // bronze, silver, gold, platinum
            $table->integer('min_orders');
            $table->integer('max_orders')->nullable(); // null = tidak terbatas
            $table->unsignedBigInteger('bonus_amount');
            $table->unsignedBigInteger('rating_bonus')->default(0);
            $table->decimal('rating_threshold', 2, 1)->default(4.8);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_rates');
    }
};
