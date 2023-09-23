<?php

namespace Database\Factories;

use App\Models\DamageAssessment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DamageAssessmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DamageAssessment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Type' => $this->faker->word,
        'ObjectName' => $this->faker->word,
        'Feeder' => $this->faker->word,
        'Town' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'DateFixed' => $this->faker->date('Y-m-d H:i:s'),
        'CrewAssigned' => $this->faker->word,
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
