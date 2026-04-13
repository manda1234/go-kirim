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
        $table->string('period')->nullable();   // ← tanpa after()
        $table->string('note')->nullable();      // ← tanpa after()
    });
}

public function down(): void
{
    Schema::table('mitra_earnings', function (Blueprint $table) {
        $table->dropColumn(['period', 'note']);
    });
}
};
