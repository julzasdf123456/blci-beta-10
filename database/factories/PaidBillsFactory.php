<?php

namespace Database\Factories;

use App\Models\PaidBills;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaidBillsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaidBills::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'BillNumber' => $this->faker->word,
        'AccountNumber' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'DCRNumber' => $this->faker->word,
        'KwhUsed' => $this->faker->word,
        'Teller' => $this->faker->word,
        'OfficeTransacted' => $this->faker->word,
        'PostingDate' => $this->faker->word,
        'PostingTime' => $this->faker->word,
        'Surcharge' => $this->faker->word,
        'Form2307TwoPercent' => $this->faker->word,
        'Form2307FivePercent' => $this->faker->word,
        'AdditionalCharges' => $this->faker->word,
        'Deductions' => $this->faker->word,
        'NetAmount' => $this->faker->word,
        'Source' => $this->faker->word,
        'ObjectSourceId' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
