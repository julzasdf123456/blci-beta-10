<?php

namespace Database\Factories;

use App\Models\KwhSales;
use Illuminate\Database\Eloquent\Factories\Factory;

class KwhSalesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KwhSales::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServicePeriod' => $this->faker->word,
        'Town' => $this->faker->word,
        'BilledKwh' => $this->faker->word,
        'ConsumedKwh' => $this->faker->word,
        'NoOfConsumers' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
