<?php

namespace Database\Factories;

use App\Models\PendingBillAdjustments;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendingBillAdjustmentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PendingBillAdjustments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ReadingId' => $this->faker->word,
        'KwhUsed' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'Confirmed' => $this->faker->word,
        'ReadDate' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
