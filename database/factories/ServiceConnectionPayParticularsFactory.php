<?php

namespace Database\Factories;

use App\Models\ServiceConnectionPayParticulars;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionPayParticularsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionPayParticulars::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Particular' => $this->faker->word,
        'Description' => $this->faker->word,
        'VatPercentage' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
