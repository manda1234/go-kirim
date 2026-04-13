<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('vehicle_type', ['motor', 'mobil']);
            $table->decimal('rate_per_km', 10, 2);              // Tarif per KM
            $table->decimal('base_fare', 10, 2)->default(0);    // Tarif dasar
            $table->decimal('service_fee', 10, 2)->default(2000); // Biaya layanan flat
            $table->decimal('platform_commission', 5, 2)->default(10.00); // % komisi
            $table->decimal('min_fare', 10, 2)->default(5000);  // Ongkir minimum
            $table->boolean('is_active')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique('vehicle_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_rates');
    }
};