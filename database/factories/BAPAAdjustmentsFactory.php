<?php

namespace Database\Factories;

use App\Models\BAPAAdjustments;
use Illuminate\Database\Eloquent\Factories\Factory;

class BAPAAdjustmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BAPAAdjustments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BAPAName' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'DiscountPercentage' => $this->faker->word,
        'DiscountAmount' => $this->faker->word,
        'NumberOfConsumers' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'NetAmount' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Route' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
