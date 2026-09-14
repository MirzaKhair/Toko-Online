<?php

namespace Database\Factories;

use App\Models\PaymentProof;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentProofFactory extends Factory
{
    protected $model = PaymentProof::class;

    public function definition(): array
    {
        return [
            'order_id' => null,
            'file_path' => 'payment-proofs/' . fake()->uuid() . '.png',
            'status' => 'pending',
            'verified_at' => null,
            'verified_by' => null,
            'admin_note' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => User::factory()->create(['role' => 'admin'])->id,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => User::factory()->create(['role' => 'admin'])->id,
            'admin_note' => 'Bukti tidak jelas.',
        ]);
    }
}
