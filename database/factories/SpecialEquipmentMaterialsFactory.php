<?php

namespace Database\Factories;

use App\Models\SpecialEquipmentMaterials;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialEquipmentMaterialsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecialEquipmentMaterials::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'NEACode' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
