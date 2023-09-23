<?php

namespace Database\Factories;

use App\Models\ProjectCodes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectCodesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectCodes::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ProjectCode' => $this->faker->word,
        'ProjectDescription' => $this->faker->word,
        'ProjectCategory' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
