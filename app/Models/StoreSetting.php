<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'logo',
        'description',
        'address',
        'phone',
        'email',
        'qris_image',
        'default_shipping_cost',
    ];

    protected $casts = [
        'default_shipping_cost' => 'decimal:2',
    ];

    public static function getSingleton(): self
    {
        return static::firstOrCreate([], [
            'store_name' => 'Toko Online',
        ]);
    }
}
