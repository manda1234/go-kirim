<?php

namespace App\Services;

use App\Models\BonusRate;
use App\Models\MitraEarning;
use App\Models\Order;
use App\Services\BonusService;


class BonusService
{
    public function calculate($user, string $period): array
    {
        [$start, $end] = match ($period) {
            'today' => [today()->startOfDay(),  today()->endOfDay()],
            'month' => [now()->startOfMonth(),  now()->endOfMonth()],
            default => [now()->startOfWeek(),   now()->endOfWeek()],
        };

        $orderCount = MitraEarning::where('mitra_id', $user->id)
            ->where('type', 'order')
            ->whereBetween('earned_date', [$start, $end])
            ->count();

        $avgRating = Order::where('mitra_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$start, $end])
            ->whereNotNull('customer_rating')
            ->avg('customer_rating') ?? 0;

        $rate = BonusRate::where('is_active', true)
            ->where('min_orders', '<=', $orderCount)
            ->where(function ($q) use ($orderCount) {
                $q->whereNull('max_orders')
                  ->orWhere('max_orders', '>=', $orderCount);
            })
            ->orderByDesc('min_orders')
            ->first();

        $tierBonus   = $rate?->bonus_amount ?? 0;
        $ratingBonus = ($avgRating >= ($rate?->rating_threshold ?? 4.8))
                       ? ($rate?->rating_bonus ?? 0) : 0;

        $nextTier = BonusRate::where('is_active', true)
            ->where('min_orders', '>', $rate?->min_orders ?? 0)
            ->orderBy('min_orders')
            ->first();

        return [
            'tier'         => $rate?->tier_name ?? 'bronze',
            'order_count'  => $orderCount,
            'avg_rating'   => round($avgRating, 1),
            'tier_bonus'   => $tierBonus,
            'rating_bonus' => $ratingBonus,
            'total_bonus'  => $tierBonus + $ratingBonus,
            'next_tier'    => $nextTier,
        ];
    }

    
}