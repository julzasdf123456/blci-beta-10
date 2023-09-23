<?php

namespace Database\Factories;

use App\Models\BillingMeters;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingMetersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillingMeters::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceAccountId' => $this->faker->word,
        'SerialNumber' => $this->faker->word,
        'SealNumber' => $this->faker->word,
        'Brand' => $this->faker->word,
        'Model' => $this->faker->word,
        'Multiplier' => $this->faker->word,
        'Status' => $this->faker->word,
        'ConnectionDate' => $this->faker->word,
        'LatestReadingDate' => $this->faker->date('Y-m-d H:i:s'),
        'DateDisconnected' => $this->faker->word,
        'DateTransfered' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
