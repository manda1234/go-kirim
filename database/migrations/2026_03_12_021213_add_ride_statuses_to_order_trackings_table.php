<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement("ALTER TABLE order_trackings MODIFY COLUMN status ENUM(
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
        ) NOT NULL");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE order_trackings MODIFY COLUMN status ENUM(
            'waiting',
            'accepted',
            'picking_up',
            'in_progress',
            'delivered',
            'completed',
            'cancelled'
        ) NOT NULL");
    }
};