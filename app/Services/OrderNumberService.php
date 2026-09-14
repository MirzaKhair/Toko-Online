<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    public static function generate(): string
    {
        $today = now()->format('Ymd');
        $prefix = 'ORD-' . $today . '-';

        return DB::transaction(function () use ($prefix, $today) {
            $count = Order::where('order_number', 'like', $prefix . '%')
                ->lockForUpdate()
                ->count();

            $sequence = $count + 1;

            return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        });
    }
}
