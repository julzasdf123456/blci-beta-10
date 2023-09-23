<?php

namespace Database\Factories;

use App\Models\DisconnectionHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisconnectionHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisconnectionHistory::class;

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
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word,
        'BillId' => $this->faker->word,
        'DisconnectionPayment' => $this->faker->word,
        'Status' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
