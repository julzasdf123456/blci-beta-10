<?php

namespace Database\Factories;

use App\Models\BillOfMaterialsIndex;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillOfMaterialsIndexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillOfMaterialsIndex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Date' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'LaborCost' => $this->faker->word,
        'Others' => $this->faker->word,
        'Total' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
