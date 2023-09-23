<?php

namespace Database\Factories;

use App\Models\BillOfMaterialsMatrix;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillOfMaterialsMatrixFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillOfMaterialsMatrix::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'StructureAssigningId' => $this->faker->word,
        'StructureId' => $this->faker->word,
        'MaterialsId' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
