<?php

namespace Database\Factories;

use App\Models\Banks;
use Illuminate\Database\Eloquent\Factories\Factory;

class BanksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Banks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BankFullName' => $this->faker->word,
        'BankAbbrev' => $this->faker->word,
        'Address' => $this->faker->word,
        'TIN' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
