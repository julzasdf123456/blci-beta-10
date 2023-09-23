<?php

namespace Database\Factories;

use App\Models\KatasNgVatTotal;
use Illuminate\Database\Eloquent\Factories\Factory;

class KatasNgVatTotalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KatasNgVatTotal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Balance' => $this->faker->word,
        'SeriesNo' => $this->faker->word,
        'Description' => $this->faker->word,
        'Year' => $this->faker->word,
        'UserId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
