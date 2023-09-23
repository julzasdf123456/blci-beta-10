<?php

namespace Database\Factories;

use App\Models\Towns;
use Illuminate\Database\Eloquent\Factories\Factory;

class TownsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Towns::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Town' => $this->faker->word,
        'District' => $this->faker->word,
        'Station' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
