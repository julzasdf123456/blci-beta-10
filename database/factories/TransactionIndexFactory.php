<?php

namespace Database\Factories;

use App\Models\TransactionIndex;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionIndexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionIndex::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'TransactionNumber' => $this->faker->word,
        'PaymentTitle' => $this->faker->word,
        'PaymentDetails' => $this->faker->word,
        'ORNumber' => $this->faker->word,
        'ORDate' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'VAT' => $this->faker->word,
        'Total' => $this->faker->word,
        'Notes' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'ServiceConnectionId' => $this->faker->word,
        'TicketId' => $this->faker->word,
        'ObjectId' => $this->faker->word,
        'Source' => $this->faker->word
        ];
    }
}
