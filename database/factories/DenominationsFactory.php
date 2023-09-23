<?php

namespace Database\Factories;

use App\Models\Denominations;
use Illuminate\Database\Eloquent\Factories\Factory;

class DenominationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Denominations::class;

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
        'OneThousand' => $this->faker->word,
        'FiveHundred' => $this->faker->word,
        'OneHundred' => $this->faker->word,
        'Fifty' => $this->faker->word,
        'Twenty' => $this->faker->word,
        'Ten' => $this->faker->word,
        'Five' => $this->faker->word,
        'Peso' => $this->faker->word,
        'Cents' => $this->faker->word,
        'PaidBillId' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'Total' => $this->faker->word
        ];
    }
}
