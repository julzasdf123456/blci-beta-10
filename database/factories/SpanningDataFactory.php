<?php

namespace Database\Factories;

use App\Models\SpanningData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpanningDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpanningData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'PrimarySpan' => $this->faker->word,
        'PrimarySize' => $this->faker->word,
        'PrimaryType' => $this->faker->word,
        'NeutralSpan' => $this->faker->word,
        'NeutralSize' => $this->faker->word,
        'NeutralType' => $this->faker->word,
        'SecondarySpan' => $this->faker->word,
        'SecondarySize' => $this->faker->word,
        'SecondaryType' => $this->faker->word,
        'SDWSpan' => $this->faker->word,
        'SDWSize' => $this->faker->word,
        'SDWType' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
