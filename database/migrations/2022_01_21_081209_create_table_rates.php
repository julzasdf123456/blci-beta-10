<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_Rates', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('RateFor', 100)->nullable(); // RATE FOR A SPECIFIC AREA
            $table->string('AreaCode')->nullable();
            $table->string('ConsumerType', 100)->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('GenerationSystemCharge', 20)->nullable();
            $table->string('TransmissionDeliveryChargeKW', 20)->nullable();
            $table->string('TransmissionDeliveryChargeKWH', 20)->nullable();
            $table->string('SystemLossCharge', 20)->nullable();
            $table->string('DistributionDemandCharge', 20)->nullable();
            $table->string('DistributionSystemCharge', 20)->nullable();
            $table->string('SupplyRetailCustomerCharge', 20)->nullable();
            $table->string('SupplySystemCharge', 20)->nullable();
            $table->string('MeteringRetailCustomerCharge', 20)->nullable();
            $table->string('MeteringSystemCharge', 20)->nullable();
            $table->string('RFSC', 20)->nullable();
            $table->string('LifelineRate', 20)->nullable();
            $table->string('InterClassCrossSubsidyCharge', 20)->nullable();
            $table->string('PPARefund', 20)->nullable();
            $table->string('SeniorCitizenSubsidy', 20)->nullable();
            $table->string('MissionaryElectrificationCharge', 20)->nullable();
            $table->string('EnvironmentalCharge', 20)->nullable();
            $table->string('StrandedContractCosts', 20)->nullable();
            $table->string('NPCStrandedDebt', 20)->nullable();
            $table->string('FeedInTariffAllowance', 20)->nullable();
            $table->string('MissionaryElectrificationREDCI', 20)->nullable();
            $table->string('GenerationVAT', 20)->nullable();
            $table->string('TransmissionVAT', 20)->nullable();
            $table->string('SystemLossVAT', 20)->nullable();
            $table->string('DistributionVAT', 20)->nullable();
            $table->string('RealPropertyTax', 20)->nullable();
            $table->string('TotalRateVATExcluded', 20)->nullable();
            $table->string('TotalRateVATIncluded', 20)->nullable();
            $table->string('OtherGenerationRateAdjustment', 20)->nullable();
            $table->string('OtherTransmissionCostAdjustmentKW', 20)->nullable();
            $table->string('OtherTransmissionCostAdjustmentKWH', 20)->nullable();
            $table->string('OtherSystemLossCostAdjustment', 20)->nullable();
            $table->string('OtherLifelineRateCostAdjustment', 20)->nullable();
            $table->string('SeniorCitizenDiscountAndSubsidyAdjustment', 20)->nullable();
            $table->string('FranchiseTax', 20)->nullable();
            $table->string('BusinessTax', 20)->nullable();
            $table->string('TotalRateVATExcludedWithAdjustments', 20)->nullable();
            $table->string('UserId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Billing_Rates');
    }
}
