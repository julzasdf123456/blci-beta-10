<?php

namespace Database\Factories;

use App\Models\ORCancellations;
use Illuminate\Database\Eloquent\Factories\Factory;

class ORCancellationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ORCancellations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'From' => $this->faker->word,
        'ObjectId' => $this->faker->word,
        'DateTimeFiled' => $this->faker->date('Y-m-d H:i:s'),
        'DateTimeApproved' => $this->faker->date('Y-m-d H:i:s'),
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
