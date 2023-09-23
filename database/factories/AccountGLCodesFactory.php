<?php

namespace Database\Factories;

use App\Models\AccountGLCodes;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountGLCodesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountGLCodes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountCode' => $this->faker->word,
        'NEACode' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
