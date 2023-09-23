<?php

namespace Database\Factories;

use App\Models\TicketLogs;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketLogsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TicketLogs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'TicketId' => $this->faker->word,
        'Log' => $this->faker->word,
        'LogDetails' => $this->faker->word,
        'LogType' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
