<?php

namespace Database\Factories;

use App\Models\EventAttendees;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventAttendeesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventAttendees::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EventId' => $this->faker->word,
        'HaveAttended' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'Name' => $this->faker->word,
        'Address' => $this->faker->word,
        'RegisteredAt' => $this->faker->word,
        'RegistationMedium' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
