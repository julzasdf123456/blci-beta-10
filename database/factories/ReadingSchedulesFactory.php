<?php

namespace Database\Factories;

use App\Models\ReadingSchedules;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingSchedulesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReadingSchedules::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AreaCode' => $this->faker->word,
        'GroupCode' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'ScheduledDate' => $this->faker->word,
        'MeterReader' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
