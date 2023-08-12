<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketerProduct>
 */
class MarketerProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'marketer_id' => 1,
            'product_id' => 1,
            'view_count' => 0,
            'creation_date' => Carbon::now()->format('Y-m-d'),
        ];
    }
}
