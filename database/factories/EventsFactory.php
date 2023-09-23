<?php

namespace Database\Factories;

use App\Models\Events;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Events::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'EventTitle' => $this->faker->word,
        'EventDescription' => $this->faker->word,
        'EventStart' => $this->faker->date('Y-m-d H:i:s'),
        'EventEnd' => $this->faker->date('Y-m-d H:i:s'),
        'RegistrationStart' => $this->faker->date('Y-m-d H:i:s'),
        'RegistrationEnd' => $this->faker->date('Y-m-d H:i:s'),
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
