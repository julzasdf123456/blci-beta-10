<?php

namespace Database\Factories;

use App\Models\MemberConsumerTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberConsumerTypesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MemberConsumerTypes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Type' => $this->faker->word,
        'Description' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
