<?php

namespace Database\Factories;

use App\Models\MeterReaderTracks;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterReaderTracksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeterReaderTracks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'TrackNameId' => $this->faker->word,
        'Latitude' => $this->faker->word,
        'Longitude' => $this->faker->word
        ];
    }
}
