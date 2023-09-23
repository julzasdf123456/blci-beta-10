<?php

namespace Database\Factories;

use App\Models\ServiceConnectionPayTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionPayTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionPayTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'Particular' => $this->faker->word,
        'Amount' => $this->faker->word,
        'Vat' => $this->faker->word,
        'Total' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
