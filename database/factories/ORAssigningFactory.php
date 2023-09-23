<?php

namespace Database\Factories;

use App\Models\ORAssigning;
use Illuminate\Database\Eloquent\Factories\Factory;

class ORAssigningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ORAssigning::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ORNumber' => $this->faker->word,
        'UserId' => $this->faker->word,
        'DateAssigned' => $this->faker->word,
        'IsSetManually' => $this->faker->word,
        'TimeAssigned' => $this->faker->word,
        'Office' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
