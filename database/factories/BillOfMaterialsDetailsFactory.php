<?php

namespace Database\Factories;

use App\Models\BillOfMaterialsDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillOfMaterialsDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillOfMaterialsDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BillOfMaterialsId' => $this->faker->word,
        'NeaCode' => $this->faker->word,
        'Description' => $this->faker->word,
        'Rate' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'Amount' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
