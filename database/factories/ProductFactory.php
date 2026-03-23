<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(0, 100);

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 100, 50000),
            'quantity' => $quantity,

            // ✅ Auto logic (IMPORTANT)
            'in_stock' => $quantity > 0 ? true : false,

            // optional image
            'image' => null,
        ];
    }
}