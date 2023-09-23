<?php

namespace Database\Factories;

use App\Models\PreDefinedMaterialsMatrix;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreDefinedMaterialsMatrixFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreDefinedMaterialsMatrix::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'NEACode' => $this->faker->word,
        'Description' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'Options' => $this->faker->word,
        'ApplicationType' => $this->faker->word,
        'Cost' => $this->faker->word,
        'LaborCost' => $this->faker->word,
        'Notes' => $this->faker->word,
        'LaborPercentage' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
