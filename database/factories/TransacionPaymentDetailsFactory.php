<?php

namespace Database\Factories;

use App\Models\TransacionPaymentDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransacionPaymentDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransacionPaymentDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'TransactionIndexId' => $this->faker->word,
        'Amount' => $this->faker->word,
        'PaymentUsed' => $this->faker->word,
        'Bank' => $this->faker->word,
        'CheckNo' => $this->faker->word,
        'CheckExpiration' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
