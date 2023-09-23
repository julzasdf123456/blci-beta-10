<?php

namespace Database\Factories;

use App\Models\BAPAPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

class BAPAPaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BAPAPayments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BAPAName' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'TwoPercentDiscount' => $this->faker->word,
        'FivePercentDiscount' => $this->faker->word,
        'AdditionalCharges' => $this->faker->word,
        'Deductions' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word,
        'Teller' => $this->faker->word,
        'NoOfConsumersPaid' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
