<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 5, 50),
            'description' => $this->faker->sentence,
            'available_quantity' => $this->faker->numberBetween(1, 100),
            'discount' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
