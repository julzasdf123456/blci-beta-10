<?php

namespace Database\Factories;

use App\Models\AccountPayables;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountPayablesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountPayables::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountCode' => $this->faker->word,
        'AccountTitle' => $this->faker->word,
        'AccountDescription' => $this->faker->word,
        'DefaultAmount' => $this->faker->word,
        'VATPercentage' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
