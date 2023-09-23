<?php

namespace Database\Factories;

use App\Models\CacheOtherPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

class CacheOtherPaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CacheOtherPayments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'TransactionIndexId' => $this->faker->word,
        'Particular' => $this->faker->word,
        'Amount' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word,
        'AccountCode' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
