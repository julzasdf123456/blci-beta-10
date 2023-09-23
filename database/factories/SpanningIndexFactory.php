<?php

namespace Database\Factories;

use App\Models\SpanningIndex;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpanningIndexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpanningIndex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'NeaCode' => $this->faker->word,
        'Structure' => $this->faker->word,
        'Description' => $this->faker->word,
        'Size' => $this->faker->word,
        'Type' => $this->faker->word,
        'SpliceNeaCode' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
