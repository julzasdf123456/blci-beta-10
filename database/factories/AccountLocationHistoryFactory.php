<?php

namespace Database\Factories;

use App\Models\AccountLocationHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountLocationHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountLocationHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'Town' => $this->faker->word,
        'Barangay' => $this->faker->word,
        'Purok' => $this->faker->word,
        'AreaCode' => $this->faker->word,
        'SequenceCode' => $this->faker->word,
        'MeterReader' => $this->faker->word,
        'ServiceConnectionId' => $this->faker->word,
        'RelocationDate' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
