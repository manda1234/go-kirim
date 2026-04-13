<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('mitra_earnings', function (Blueprint $table) {
        $table->enum('type', ['order', 'bonus', 'manual'])->default('order')->after('order_id');
    });
}

public function down(): void
{
    Schema::table('mitra_earnings', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
