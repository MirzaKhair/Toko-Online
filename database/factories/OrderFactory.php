<?php

namespace Database\Factories;

use App\Models\Order;
use App\Services\OrderNumberService;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => OrderNumberService::generate(),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->numerify('08##########'),
            'customer_address' => fake()->address(),
            'customer_note' => fake()->optional()->sentence(),
            'subtotal' => fake()->randomFloat(2, 50000, 5000000),
            'shipping_cost' => 0,
            'total_amount' => fake()->randomFloat(2, 50000, 5000000),
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'admin_note' => null,
            'shipping_finalized_at' => null,
        ];
    }

    public function qris(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'qris',
        ]);
    }

    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'paid',
        ]);
    }

    public function waitingVerification(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'waiting_verification',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'confirmed',
            'shipping_finalized_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'pending',
            'shipping_finalized_at' => null,
        ]);
    }
}
