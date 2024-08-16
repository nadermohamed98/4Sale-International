<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fromTime = $this->faker->dateTimeBetween('now', '+1 month');
        $toTime = (clone $fromTime)->modify('+2 hours');

        return [
            'table_id' => Table::pluck('id')->random(),
            'customer_id' => Customer::pluck('id')->random(),
            'from_time' => $fromTime,
            'to_time' => $toTime,
        ];
    }
}
