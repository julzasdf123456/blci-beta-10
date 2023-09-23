<?php

namespace Database\Factories;

use App\Models\BAPAAdjustmentDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class BAPAAdjustmentDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BAPAAdjustmentDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'AccountNumber' => $this->faker->word,
        'BillId' => $this->faker->word,
        'DiscountPercentage' => $this->faker->word,
        'DiscountAmount' => $this->faker->word,
        'BAPAName' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
