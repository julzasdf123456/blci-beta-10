<?php

namespace Database\Factories;

use App\Models\ServiceConnectionImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionImagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionImages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Photo' => $this->faker->word,
        'ServiceConnectionId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
