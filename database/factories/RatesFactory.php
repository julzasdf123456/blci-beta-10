<?php

namespace Database\Factories;

use App\Models\Rates;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rates::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'RateFor' => $this->faker->word,
        'ConsumerType' => $this->faker->word,
        'ServicePeriod' => $this->faker->word,
        'Notes' => $this->faker->word,
        'GenerationSystemCharge' => $this->faker->word,
        'TransmissionDeliveryChargeKW' => $this->faker->word,
        'TransmissionDeliveryChargeKWH' => $this->faker->word,
        'SystemLossCharge' => $this->faker->word,
        'DistributionDemandCharge' => $this->faker->word,
        'DistributionSystemCharge' => $this->faker->word,
        'SupplyRetailCustomerCharge' => $this->faker->word,
        'SupplySystemCharge' => $this->faker->word,
        'MeteringRetailCustomerCharge' => $this->faker->word,
        'MeteringSystemCharge' => $this->faker->word,
        'RFSC' => $this->faker->word,
        'LifelineRate' => $this->faker->word,
        'InterClassCrossSubsidyCharge' => $this->faker->word,
        'PPARefund' => $this->faker->word,
        'SeniorCitizenSubsidy' => $this->faker->word,
        'MissionaryElectrificationCharge' => $this->faker->word,
        'EnvironmentalCharge' => $this->faker->word,
        'StrandedContractCosts' => $this->faker->word,
        'NPCStrandedDebt' => $this->faker->word,
        'FeedInTariffAllowance' => $this->faker->word,
        'MissionaryElectrificationREDCI' => $this->faker->word,
        'GenerationVAT' => $this->faker->word,
        'TransmissionVAT' => $this->faker->word,
        'SystemLossVAT' => $this->faker->word,
        'DistributionVAT' => $this->faker->word,
        'TotalRateVATExcluded' => $this->faker->word,
        'TotalRateVATIncluded' => $this->faker->word,
        'UserId' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
