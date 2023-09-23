<?php

namespace Database\Factories;

use App\Models\ServiceConnectionTotalPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceConnectionTotalPaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceConnectionTotalPayments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'Form2307TwoPercent' => $this->faker->word,
        'Form2307FivePercent' => $this->faker->word,
        'TotalVat' => $this->faker->word,
        'Total' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
