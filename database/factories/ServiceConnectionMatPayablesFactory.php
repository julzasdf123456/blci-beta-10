<?php

namespace Database\Factories;

use App\Models\ServiceConnectionMatPayables;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionMatPayablesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionMatPayables::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Material' => $this->faker->word,
        'Rate' => $this->faker->word,
        'Description' => $this->faker->word,
        'VatPercentage' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
