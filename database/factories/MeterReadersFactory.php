<?php

namespace Database\Factories;

use App\Models\MeterReaders;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterReadersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeterReaders::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'MeterReaderCode' => $this->faker->word,
        'UserId' => $this->faker->word,
        'DeviceMacAddress' => $this->faker->word,
        'AreaCodeAssignment' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
