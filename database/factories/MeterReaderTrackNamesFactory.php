<?php

namespace Database\Factories;

use App\Models\MeterReaderTrackNames;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterReaderTrackNamesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeterReaderTrackNames::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'TrackName' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
