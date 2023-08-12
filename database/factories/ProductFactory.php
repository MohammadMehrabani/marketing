<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'url' => $this->faker->url(),
            'image' => $this->faker->image(),
            'description' => $this->faker->text(10),
            'view_count' => 0,
            'merchant_id' => 1,
        ];
    }
}
