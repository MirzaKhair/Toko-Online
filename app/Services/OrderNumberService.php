<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderNumberService
{
    public static function generate(): string
    {
        $today = now()->format('Ymd');
        $prefix = 'ORD-' . $today . '-';

        do {
            $orderNumber = $prefix . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
