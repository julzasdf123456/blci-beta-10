<?php

namespace Database\Factories;

use App\Models\BillsOriginal;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillsOriginalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BillsOriginal::class;

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
        'Multiplier' => $this->faker->word,
        'Coreloss' => $this->faker->word,
        'KwhUsed' => $this->faker->word,
        'PreviousKwh' => $this->faker->word,
        'PresentKwh' => $this->faker->word,
        'DemandPreviousKwh' => $this->faker->word,
        'DemandPresentKwh' => $this->faker->word,
        'AdditionalKwh' => $this->faker->word,
        'AdditionalDemandKwh' => $this->faker->word,
        'KwhAmount' => $this->faker->word,
        'EffectiveRate' => $this->faker->word,
        'AdditionalCharges' => $this->faker->word,
        'Deductions' => $this->faker->word,
        'NetAmount' => $this->faker->word,
        'BillingDate' => $this->faker->word,
        'ServiceDateFrom' => $this->faker->word,
        'ServiceDateTo' => $this->faker->word,
        'DueDate' => $this->faker->word,
        'MeterNumber' => $this->faker->word,
        'ConsumerType' => $this->faker->word,
        'BillType' => $this->faker->word,
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
        'RealPropertyTax' => $this->faker->word,
        'OtherGenerationRateAdjustment' => $this->faker->word,
        'OtherTransmissionCostAdjustmentKW' => $this->faker->word,
        'OtherTransmissionCostAdjustmentKWH' => $this->faker->word,
        'OtherSystemLossCostAdjustment' => $this->faker->word,
        'OtherLifelineRateCostAdjustment' => $this->faker->word,
        'SeniorCitizenDiscountAndSubsidyAdjustment' => $this->faker->word,
        'FranchiseTax' => $this->faker->word,
        'BusinessTax' => $this->faker->word,
        'AdjustmentType' => $this->faker->word,
        'AdjustmentNumber' => $this->faker->word,
        'AdjustedBy' => $this->faker->word,
        'DateAdjusted' => $this->faker->word,
        'Notes' => $this->faker->word,
        'UserId' => $this->faker->word,
        'BilledFrom' => $this->faker->word,
        'Form2307Amount' => $this->faker->word,
        'Evat2Percent' => $this->faker->word,
        'Evat5Percent' => $this->faker->word,
        'MergedToCollectible' => $this->faker->word,
        'DeductedDeposit' => $this->faker->word,
        'ExcessDeposit' => $this->faker->word,
        'AveragedCount' => $this->faker->word,
        'IsUnlockedForPayment' => $this->faker->word,
        'UnlockedBy' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
