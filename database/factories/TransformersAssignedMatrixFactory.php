<?php

namespace Database\Factories;

use App\Models\TransformersAssignedMatrix;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransformersAssignedMatrixFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransformersAssignedMatrix::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'MaterialsId' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
