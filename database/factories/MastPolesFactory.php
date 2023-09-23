<?php

namespace Database\Factories;

use App\Models\MastPoles;
use Illuminate\Database\Eloquent\Factories\Factory;

class MastPolesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MastPoles::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word,
        'DateTimeTaken' => $this->faker->date('Y-m-d H:i:s'),
        'PoleRemarks' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
