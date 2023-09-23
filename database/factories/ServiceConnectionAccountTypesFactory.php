<?php

namespace Database\Factories;

use App\Models\ServiceConnectionAccountTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionAccountTypesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionAccountTypes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountType' => $this->faker->word,
        'Description' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
