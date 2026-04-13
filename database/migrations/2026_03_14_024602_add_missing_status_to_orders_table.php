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
    DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
        'waiting',
        'accepted',
        'picking_up',
        'in_progress',
        'delivered',
        'completed',
        'cancelled',
        'confirmed',
        'on_the_way',
        'picked_up',
        'arrived'
    ) NOT NULL DEFAULT 'waiting'");
}

public function down(): void
{
    DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
        'waiting',
        'accepted',
        'picking_up',
        'in_progress',
        'delivered',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'waiting'");
}
};
