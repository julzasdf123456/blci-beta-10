<?php

namespace Database\Factories;

use App\Models\CostCenters;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCentersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CostCenters::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'CostCode' => $this->faker->word,
        'CostName' => $this->faker->word,
        'CostDepartment' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
