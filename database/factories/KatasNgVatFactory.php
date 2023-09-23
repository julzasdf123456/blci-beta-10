<?php

namespace Database\Factories;

use App\Models\KatasNgVat;
use Illuminate\Database\Eloquent\Factories\Factory;

class KatasNgVatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KatasNgVat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'Balance' => $this->faker->word,
        'SeriesNo' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
