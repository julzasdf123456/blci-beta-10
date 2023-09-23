<?php

namespace Database\Factories;

use App\Models\ServiceConnectionMatPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionMatPaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionMatPayments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Material' => $this->faker->word,
        'Quantity' => $this->faker->word,
        'Vat' => $this->faker->word,
        'Total' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
