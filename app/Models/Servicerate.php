<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceRate extends Model {
    protected $fillable = ['vehicle_type','rate_per_km','base_fare','service_fee','platform_commission','min_fare','is_active','updated_by'];
    protected $casts    = ['is_active' => 'boolean', 'rate_per_km' => 'float', 'platform_commission' => 'float'];

    public static function forVehicle(string $type): ?self {
        return static::where('vehicle_type', $type)->where('is_active', true)->first();
    }

    public function calculateFare(float $distanceKm): array {
        $distanceFare = $distanceKm * $this->rate_per_km;
        $total        = max($this->base_fare + $distanceFare + $this->service_fee, $this->min_fare);
        $platformFee  = $total * ($this->platform_commission / 100);
        return [
            'distance_km'    => $distanceKm,
            'base_fare'      => $this->base_fare,
            'distance_fare'  => $distanceFare,
            'service_fee'    => $this->service_fee,
            'total_fare'     => round($total),
            'platform_fee'   => round($platformFee),
            'mitra_earning'  => round($total - $platformFee),
        ];
    }
}