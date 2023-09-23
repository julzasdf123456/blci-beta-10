<?php

namespace Database\Factories;

use App\Models\ArrearsLedgerDistribution;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArrearsLedgerDistributionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArrearsLedgerDistribution::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'Amount' => $this->faker->word,
        'IsBilled' => $this->faker->word,
        'IsPaid' => $this->faker->word,
        'LinkedBillNumber' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
