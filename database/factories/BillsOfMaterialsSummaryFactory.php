<?php

namespace Database\Factories;

use App\Models\BillsOfMaterialsSummary;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillsOfMaterialsSummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillsOfMaterialsSummary::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServiceConnectionId' => $this->faker->word,
        'ExcludeTransformerLaborCost' => $this->faker->word,
        'TransformerChangedPrice' => $this->faker->word,
        'MonthDuration' => $this->faker->word,
        'TransformerLaborCostPercentage' => $this->faker->word,
        'MaterialLaborCostPercentage' => $this->faker->word,
        'HandlingCostPercentage' => $this->faker->word,
        'SubTotal' => $this->faker->word,
        'LaborCost' => $this->faker->word,
        'HandlingCost' => $this->faker->word,
        'Total' => $this->faker->word,
        'TotalVAT' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
