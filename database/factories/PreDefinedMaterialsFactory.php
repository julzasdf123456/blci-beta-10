<?php

namespace Database\Factories;

use App\Models\PreDefinedMaterials;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreDefinedMaterialsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreDefinedMaterials::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'NEACode' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'Options' => $this->faker->word,
        'ApplicationType' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
