<?php

namespace Database\Factories;

use App\Models\PaidBillsDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaidBillsDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaidBillsDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'BillId' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'Amount' => $this->faker->word,
        'PaymentUsed' => $this->faker->word,
        'CheckNo' => $this->faker->word,
        'Bank' => $this->faker->word,
        'CheckExpiration' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
