<?php

namespace Database\Factories;

use App\Models\DCRIndex;
use Illuminate\Database\Eloquent\Factories\Factory;

class DCRIndexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DCRIndex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'GLCode' => $this->faker->word,
        'NEACode' => $this->faker->word,
        'TableName' => $this->faker->word,
        'Columns' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
