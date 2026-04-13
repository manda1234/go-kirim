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
    Schema::table('order_messages', function (Blueprint $table) {
        $table->string('image_path')->nullable()->after('message');
        $table->enum('type', ['text', 'image'])->default('text')->after('image_path');
    });
}

public function down(): void
{
    Schema::table('order_messages', function (Blueprint $table) {
        $table->dropColumn(['image_path', 'type']);
    });
}
};
