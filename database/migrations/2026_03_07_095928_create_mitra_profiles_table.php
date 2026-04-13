<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('vehicle_type', ['motor', 'mobil'])->default('motor');
            $table->string('vehicle_brand', 100)->nullable();    // Cth: Honda Vario
            $table->string('vehicle_plate', 20)->nullable();     // Cth: B 1234 ABC
            $table->string('ktp_number', 20)->nullable();        // Nomor KTP
            $table->string('sim_number', 20)->nullable();        // Nomor SIM
            $table->string('ktp_photo')->nullable();
            $table->string('sim_photo')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();      // Lokasi real-time
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_online')->default(false);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_trips')->default(0);
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->enum('status', ['pending', 'verified', 'rejected', 'suspended'])->default('pending');
            $table->timestamps();

            $table->index(['is_online', 'vehicle_type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_profiles');
    }
};