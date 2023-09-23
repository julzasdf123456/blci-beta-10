<?php

namespace Database\Factories;

use App\Models\MaterialAssets;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialAssetsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MaterialAssets::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Description' => $this->faker->word,
        'Amount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
