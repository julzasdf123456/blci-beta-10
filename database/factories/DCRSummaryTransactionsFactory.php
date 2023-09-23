<?php

namespace Database\Factories;

use App\Models\DCRSummaryTransactions;
use Illuminate\Database\Eloquent\Factories\Factory;

class DCRSummaryTransactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DCRSummaryTransactions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'GLCode' => $this->faker->word,
        'NEACode' => $this->faker->word,
        'Description' => $this->faker->word,
        'Amount' => $this->faker->word,
        'Day' => $this->faker->word,
        'Time' => $this->faker->word,
        'Teller' => $this->faker->word,
        'DCRNumber' => $this->faker->word,
        'Status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
