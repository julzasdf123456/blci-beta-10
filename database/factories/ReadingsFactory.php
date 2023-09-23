<?php

namespace Database\Factories;

use App\Models\Readings;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Readings::class;

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
        'ReadingTimestamp' => $this->faker->date('Y-m-d H:i:s'),
        'KwhUsed' => $this->faker->word,
        'DemandKwhUsed' => $this->faker->word,
        'Notes' => $this->faker->word,
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
