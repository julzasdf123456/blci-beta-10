<?php

namespace Database\Factories;

use App\Models\TransactionDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'TransactionIndexId' => $this->faker->word,
        'Particular' => $this->faker->word,
        'Amount' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
