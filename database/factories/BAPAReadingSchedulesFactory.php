<?php

namespace Database\Factories;

use App\Models\BAPAReadingSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;

class BAPAReadingSchedulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BAPAReadingSchedules::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServicePeriod' => $this->faker->word,
        'Town' => $this->faker->word,
        'BAPAName' => $this->faker->word,
        'Status' => $this->faker->word,
        'DownloadedBy' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
