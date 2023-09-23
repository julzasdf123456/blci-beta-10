<?php

namespace Database\Factories;

use App\Models\BillingTransformers;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingTransformersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillingTransformers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceAccountId' => $this->faker->word,
        'TransformerNumber' => $this->faker->word,
        'Rating' => $this->faker->word,
        'RentalFee' => $this->faker->word,
        'Load' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
