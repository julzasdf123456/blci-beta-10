<?php

namespace Database\Factories;

use App\Models\DistributionSystemLoss;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistributionSystemLossFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DistributionSystemLoss::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ServicePeriod' => $this->faker->word,
        'VictoriasSubstation' => $this->faker->word,
        'SagaySubstation' => $this->faker->word,
        'SanCarlosSubstation' => $this->faker->word,
        'EscalanteSubstation' => $this->faker->word,
        'LopezSubstation' => $this->faker->word,
        'CadizSubstation' => $this->faker->word,
        'IpiSubstation' => $this->faker->word,
        'TobosoCalatravaSubstation' => $this->faker->word,
        'VictoriasMillingCompany' => $this->faker->word,
        'SanCarlosBionergy' => $this->faker->word,
        'TotalEnergyInput' => $this->faker->word,
        'EnergySales' => $this->faker->word,
        'EnergyAdjustmentRecoveries' => $this->faker->word,
        'TotalEnergyOutput' => $this->faker->word,
        'TotalSystemLoss' => $this->faker->word,
        'TotalSystemLossPercentage' => $this->faker->word,
        'UserId' => $this->faker->word,
        'From' => $this->faker->word,
        'To' => $this->faker->word,
        'Status' => $this->faker->word,
        'Notes' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
