<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Billing_Bills', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            /** BASIC BILL INFO */
            $table->string('BillNumber')->nullable();
            $table->string('AccountNumber')->nullable();
            $table->date('ServicePeriod')->nullable();
            $table->string('Multiplier')->nullable();
            $table->string('Coreloss')->nullable();
            $table->string('KwhUsed')->nullable();
            $table->string('PreviousKwh')->nullable();
            $table->string('PresentKwh')->nullable();
            $table->string('DemandPreviousKwh')->nullable();
            $table->string('DemandPresentKwh')->nullable();
            $table->string('AdditionalKwh')->nullable();
            $table->string('AdditionalDemandKwh')->nullable();
            $table->string('KwhAmount')->nullable();
            $table->string('EffectiveRate')->nullable();
            $table->string('AdditionalCharges')->nullable();
            $table->string('Deductions')->nullable();
            $table->string('NetAmount')->nullable();
            $table->date('BillingDate')->nullable();
            $table->date('ServiceDateFrom')->nullable();
            $table->date('ServiceDateTo')->nullable();
            $table->date('DueDate')->nullable();
            $table->string('MeterNumber')->nullable();  
            $table->string('ConsumerType')->nullable();    
            $table->string('BillType')->nullable();   
            
            /** RATES COMPUTATION */            
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
            $table->string('OtherGenerationRateAdjustment', 20)->nullable();
            $table->string('OtherTransmissionCostAdjustmentKW', 20)->nullable();
            $table->string('OtherTransmissionCostAdjustmentKWH', 20)->nullable();
            $table->string('OtherSystemLossCostAdjustment', 20)->nullable();
            $table->string('OtherLifelineRateCostAdjustment', 20)->nullable();
            $table->string('SeniorCitizenDiscountAndSubsidyAdjustment', 20)->nullable();
            $table->string('FranchiseTax', 20)->nullable();
            $table->string('BusinessTax', 20)->nullable();

            $table->string('AdjustmentType', 30)->nullable(); // Direct, etc
            $table->string('AdjustmentNumber', 80)->nullable();
            $table->string('AdjustedBy')->nullable();
            $table->date('DateAdjusted')->nullable();

            $table->string('Notes', 2500)->nullable();
            $table->string('UserId')->nullable();
            $table->string('BilledFrom')->nullable();

            $table->string('Form2307Amount')->nullable();
            $table->string('Evat2Percent')->nullable();
            $table->string('Evat5Percent')->nullable();

            $table->string('MergedToCollectible')->nullable(); // Yes - if merged to Collectibles, No - if not
            $table->string('DeductedDeposit')->nullable(); // FROM DEPOSITS/PREPAYMENTS
            $table->string('ExcessDeposit')->nullable(); // FROM DEPOSITS/PREPAYMENTS

            $table->string('AveragedCount')->nullable(); // Number of months that the bill is averaged

            $table->string('IsUnlockedForPayment')->nullable(); // Yes, Requested, Null
            $table->string('UnlockedBy')->nullable();

            $table->string('ForCancellation')->nullable();
            $table->string('CancelRequestedBy')->nullable();
            $table->string('CancelApprovedBy')->nullable();

            $table->decimal('KatasNgVat')->nullable();
            
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
        Schema::dropIfExists('Billing_Bills');
    }
}
