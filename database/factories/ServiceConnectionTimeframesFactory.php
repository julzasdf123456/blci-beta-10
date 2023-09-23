<?php

namespace Database\Factories;

use App\Models\ServiceConnectionTimeframes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionTimeframesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionTimeframes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
