<?php

namespace Database\Factories;

use App\Models\ServiceConnectionCrew;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionCrewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionCrew::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'StationName' => $this->faker->word,
        'CrewLeader' => $this->faker->word,
        'Members' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
