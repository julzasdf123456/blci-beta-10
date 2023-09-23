<?php

namespace Database\Factories;

use App\Models\PrePaymentTransHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrePaymentTransHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrePaymentTransHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'Method' => $this->faker->word,
        'Amount' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
