<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillMirror extends Model
{
    public $table = 'Cashier_BillMirror';

    protected $connection = 'sqlsrv';
    
    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BillNumber',
        'AccountNumber',
        'ServicePeriod',
        'AdditionalCharges',
        'Deductions',
        'NetAmount',
        'BillingDate',
        'ServiceDateFrom',
        'ServiceDateTo',
        'DueDate',
        'GenerationSystemCharge',
        'TransmissionDeliveryChargeKW',
        'TransmissionDeliveryChargeKWH',
        'SystemLossCharge',
        'DistributionDemandCharge',
        'DistributionSystemCharge',
        'SupplyRetailCustomerCharge',
        'SupplySystemCharge',
        'MeteringRetailCustomerCharge',
        'MeteringSystemCharge',
        'RFSC',
        'LifelineRate',
        'InterClassCrossSubsidyCharge',
        'PPARefund',
        'SeniorCitizenSubsidy',
        'MissionaryElectrificationCharge',
        'EnvironmentalCharge',
        'StrandedContractCosts',
        'NPCStrandedDebt',
        'FeedInTariffAllowance',
        'MissionaryElectrificationREDCI',
        'GenerationVAT',
        'TransmissionVAT',
        'SystemLossVAT',
        'DistributionVAT',
        'RealPropertyTax',
        'Notes',
        'UserId',
        'OtherGenerationRateAdjustment',
        'OtherTransmissionCostAdjustmentKW',
        'OtherTransmissionCostAdjustmentKWH',
        'OtherSystemLossCostAdjustment',
        'OtherLifelineRateCostAdjustment',
        'SeniorCitizenDiscountAndSubsidyAdjustment',
        'FranchiseTax',
        'BusinessTax',
        'AdjustmentType',
        'Form2307Amount',
        'DeductedDeposit',
        'ExcessDeposit',
        'IsUnlockedForPayment',
        'UnlockedBy',
        'Evat2Percent',
        'Evat5Percent',
        'AdjustmentNumber',
        'AdjustedBy',
        'DateAdjusted',
        'ForCancellation',
        'CancelRequestedBy',
        'CancelApprovedBy',
        'KatasNgVat',
        'SolarImportPresent',
        'SolarImportPrevious',
        'SolarExportPresent',
        'SolarExportPrevious',
        'SolarImportKwh',
        'SolarExportKwh',
        'GenerationChargeSolarExport',
        'SolarResidualCredit',
        'SolarDemandChargeKW',
        'SolarDemandChargeKWH',
        'SolarRetailCustomerCharge',
        'SolarSupplySystemCharge',
        'SolarMeteringRetailCharge',
        'SolarMeteringSystemCharge',
        'Item1',
        'Item2',
        'Item3',
        'Item4',
        'Item5',
        'PaidAmount',
        'Balance',
        'ACRM',
        'PowerActReduction',
        'ACRMVAT',
        'MissionaryElectrificationSPUG',
        'MissionaryElectrificationSPUGTRUEUP',
        'FranchiseTaxOthers',
        'OthersVAT',
        'AdvancedMaterialDeposit',
        'CustomerDeposit',
        'TransformerRental',
        'AdjustmentRequestedBy',
        'AdjustmentApprovedBy',
        'AdjustmentStatus',
        'DateAdjustmentRequested',
        'TermedPayments',
        'ORNumber',
        'ORDate',
        'BatchNumber',
        'Teller',
        'PaidBillId'
    ];

    protected $casts = [
        'id' => 'string',
        'BillNumber' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'date',
        'AdditionalCharges' => 'decimal:2',
        'Deductions' => 'decimal:2',
        'NetAmount' => 'decimal:2',
        'BillingDate' => 'date',
        'ServiceDateFrom' => 'date',
        'ServiceDateTo' => 'date',
        'DueDate' => 'date',
        'GenerationSystemCharge' => 'decimal:2',
        'TransmissionDeliveryChargeKW' => 'decimal:2',
        'TransmissionDeliveryChargeKWH' => 'decimal:2',
        'SystemLossCharge' => 'decimal:2',
        'DistributionDemandCharge' => 'decimal:2',
        'DistributionSystemCharge' => 'decimal:2',
        'SupplyRetailCustomerCharge' => 'decimal:2',
        'SupplySystemCharge' => 'decimal:2',
        'MeteringRetailCustomerCharge' => 'decimal:2',
        'MeteringSystemCharge' => 'decimal:2',
        'RFSC' => 'decimal:2',
        'LifelineRate' => 'decimal:2',
        'InterClassCrossSubsidyCharge' => 'decimal:2',
        'PPARefund' => 'decimal:2',
        'SeniorCitizenSubsidy' => 'decimal:2',
        'MissionaryElectrificationCharge' => 'decimal:2',
        'EnvironmentalCharge' => 'decimal:2',
        'StrandedContractCosts' => 'decimal:2',
        'NPCStrandedDebt' => 'decimal:2',
        'FeedInTariffAllowance' => 'decimal:2',
        'MissionaryElectrificationREDCI' => 'decimal:2',
        'GenerationVAT' => 'decimal:2',
        'TransmissionVAT' => 'decimal:2',
        'SystemLossVAT' => 'decimal:2',
        'DistributionVAT' => 'decimal:2',
        'RealPropertyTax' => 'decimal:2',
        'Notes' => 'string',
        'UserId' => 'string',
        'OtherGenerationRateAdjustment' => 'decimal:2',
        'OtherTransmissionCostAdjustmentKW' => 'decimal:2',
        'OtherTransmissionCostAdjustmentKWH' => 'decimal:2',
        'OtherSystemLossCostAdjustment' => 'decimal:2',
        'OtherLifelineRateCostAdjustment' => 'decimal:2',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'decimal:2',
        'FranchiseTax' => 'decimal:2',
        'BusinessTax' => 'decimal:2',
        'AdjustmentType' => 'string',
        'Form2307Amount' => 'string',
        'DeductedDeposit' => 'decimal:2',
        'ExcessDeposit' => 'decimal:2',
        'IsUnlockedForPayment' => 'string',
        'UnlockedBy' => 'string',
        'Evat2Percent' => 'string',
        'Evat5Percent' => 'string',
        'AdjustmentNumber' => 'string',
        'AdjustedBy' => 'string',
        'DateAdjusted' => 'date',
        'ForCancellation' => 'string',
        'CancelRequestedBy' => 'string',
        'CancelApprovedBy' => 'string',
        'SolarImportPresent' => 'decimal:2',
        'SolarImportPrevious' => 'decimal:2',
        'SolarExportPresent' => 'decimal:2',
        'SolarExportPrevious' => 'decimal:2',
        'SolarImportKwh' => 'decimal:2',
        'SolarExportKwh' => 'decimal:2',
        'GenerationChargeSolarExport' => 'decimal:2',
        'SolarResidualCredit' => 'decimal:2',
        'SolarDemandChargeKW' => 'decimal:2',
        'SolarDemandChargeKWH' => 'decimal:2',
        'SolarRetailCustomerCharge' => 'decimal:2',
        'SolarSupplySystemCharge' => 'decimal:2',
        'SolarMeteringRetailCharge' => 'decimal:2',
        'SolarMeteringSystemCharge' => 'decimal:2',
        'Item1' => 'string',
        'Item2' => 'string',
        'Item3' => 'string',
        'Item4' => 'string',
        'Item5' => 'string',
        'PaidAmount' => 'decimal:2',
        'Balance' => 'decimal:2',
        'ACRM' => 'decimal:2',
        'PowerActReduction' => 'decimal:2',
        'ACRMVAT' => 'decimal:2',
        'MissionaryElectrificationSPUG' => 'decimal:2',
        'MissionaryElectrificationSPUGTRUEUP' => 'decimal:2',
        'FranchiseTaxOthers' => 'decimal:2',
        'OthersVAT' => 'decimal:2',
        'AdvancedMaterialDeposit' => 'decimal:2',
        'CustomerDeposit' => 'decimal:2',
        'TransformerRental' => 'decimal:2',
        'AdjustmentRequestedBy' => 'string',
        'AdjustmentApprovedBy' => 'string',
        'AdjustmentStatus' => 'string',
        'DateAdjustmentRequested' => 'datetime',
        'TermedPayments' => 'decimal:2',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'BatchNumber' => 'string',
        'Teller' => 'string',
        'PaidBillId' => 'string'
    ];

    public static array $rules = [
        'BillNumber' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'AdditionalCharges' => 'nullable|numeric',
        'Deductions' => 'nullable|numeric',
        'NetAmount' => 'nullable|numeric',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'GenerationSystemCharge' => 'nullable|numeric',
        'TransmissionDeliveryChargeKW' => 'nullable|numeric',
        'TransmissionDeliveryChargeKWH' => 'nullable|numeric',
        'SystemLossCharge' => 'nullable|numeric',
        'DistributionDemandCharge' => 'nullable|numeric',
        'DistributionSystemCharge' => 'nullable|numeric',
        'SupplyRetailCustomerCharge' => 'nullable|numeric',
        'SupplySystemCharge' => 'nullable|numeric',
        'MeteringRetailCustomerCharge' => 'nullable|numeric',
        'MeteringSystemCharge' => 'nullable|numeric',
        'RFSC' => 'nullable|numeric',
        'LifelineRate' => 'nullable|numeric',
        'InterClassCrossSubsidyCharge' => 'nullable|numeric',
        'PPARefund' => 'nullable|numeric',
        'SeniorCitizenSubsidy' => 'nullable|numeric',
        'MissionaryElectrificationCharge' => 'nullable|numeric',
        'EnvironmentalCharge' => 'nullable|numeric',
        'StrandedContractCosts' => 'nullable|numeric',
        'NPCStrandedDebt' => 'nullable|numeric',
        'FeedInTariffAllowance' => 'nullable|numeric',
        'MissionaryElectrificationREDCI' => 'nullable|numeric',
        'GenerationVAT' => 'nullable|numeric',
        'TransmissionVAT' => 'nullable|numeric',
        'SystemLossVAT' => 'nullable|numeric',
        'DistributionVAT' => 'nullable|numeric',
        'RealPropertyTax' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:2500',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'OtherGenerationRateAdjustment' => 'nullable|numeric',
        'OtherTransmissionCostAdjustmentKW' => 'nullable|numeric',
        'OtherTransmissionCostAdjustmentKWH' => 'nullable|numeric',
        'OtherSystemLossCostAdjustment' => 'nullable|numeric',
        'OtherLifelineRateCostAdjustment' => 'nullable|numeric',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'nullable|numeric',
        'FranchiseTax' => 'nullable|numeric',
        'BusinessTax' => 'nullable|numeric',
        'AdjustmentType' => 'nullable|string|max:50',
        'Form2307Amount' => 'nullable|string|max:50',
        'DeductedDeposit' => 'nullable|numeric',
        'ExcessDeposit' => 'nullable|numeric',
        'IsUnlockedForPayment' => 'nullable|string|max:50',
        'UnlockedBy' => 'nullable|string|max:50',
        'Evat2Percent' => 'nullable|string|max:50',
        'Evat5Percent' => 'nullable|string|max:50',
        'AdjustmentNumber' => 'nullable|string|max:80',
        'AdjustedBy' => 'nullable|string|max:50',
        'DateAdjusted' => 'nullable',
        'ForCancellation' => 'nullable|string|max:50',
        'CancelRequestedBy' => 'nullable|string|max:50',
        'CancelApprovedBy' => 'nullable|string|max:50',
        'KatasNgVat' => 'nullable',
        'SolarImportPresent' => 'nullable|numeric',
        'SolarImportPrevious' => 'nullable|numeric',
        'SolarExportPresent' => 'nullable|numeric',
        'SolarExportPrevious' => 'nullable|numeric',
        'SolarImportKwh' => 'nullable|numeric',
        'SolarExportKwh' => 'nullable|numeric',
        'GenerationChargeSolarExport' => 'nullable|numeric',
        'SolarResidualCredit' => 'nullable|numeric',
        'SolarDemandChargeKW' => 'nullable|numeric',
        'SolarDemandChargeKWH' => 'nullable|numeric',
        'SolarRetailCustomerCharge' => 'nullable|numeric',
        'SolarSupplySystemCharge' => 'nullable|numeric',
        'SolarMeteringRetailCharge' => 'nullable|numeric',
        'SolarMeteringSystemCharge' => 'nullable|numeric',
        'Item1' => 'nullable|string|max:50',
        'Item2' => 'nullable|string|max:255',
        'Item3' => 'nullable|string|max:100',
        'Item4' => 'nullable|string|max:50',
        'Item5' => 'nullable|string|max:50',
        'PaidAmount' => 'nullable|numeric',
        'Balance' => 'nullable|numeric',
        'ACRM' => 'nullable|numeric',
        'PowerActReduction' => 'nullable|numeric',
        'ACRMVAT' => 'nullable|numeric',
        'MissionaryElectrificationSPUG' => 'nullable|numeric',
        'MissionaryElectrificationSPUGTRUEUP' => 'nullable|numeric',
        'FranchiseTaxOthers' => 'nullable|numeric',
        'OthersVAT' => 'nullable|numeric',
        'AdvancedMaterialDeposit' => 'nullable|numeric',
        'CustomerDeposit' => 'nullable|numeric',
        'TransformerRental' => 'nullable|numeric',
        'AdjustmentRequestedBy' => 'nullable|string|max:50',
        'AdjustmentApprovedBy' => 'nullable|string|max:50',
        'AdjustmentStatus' => 'nullable|string|max:50',
        'DateAdjustmentRequested' => 'nullable',
        'TermedPayments' => 'nullable|numeric',
        'ORNumber' => 'nullable|string|max:50',
        'ORDate' => 'nullable',
        'BatchNumber' => 'nullable|string|max:90',
        'Teller' => 'nullable|string|max:50',
        'PaidBillId' => 'nullable|string|max:100'
    ];

    public static function distributeUpdate($amount, $charges, $existingValue) {
        $remaining = $charges - $existingValue;

        if ($amount >= $remaining) {
            return $remaining;
        } else {
            $dif = $remaining - $amount;
            return $remaining - $dif;
        }
    }

    public static function populateTermedPaymentAmountUpdate($amount, $bill, $billMirror) {
        // TermedPayments
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->TermedPayments);
            $existingValue = BillMirror::doubleNull($billMirror->TermedPayments);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->TermedPayments = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        return $amount;
    }

    public static function populateOtherAmountUpdate($amount, $bill, $billMirror) {
        // FranchiseTax
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->FranchiseTax);
            $existingValue = BillMirror::doubleNull($billMirror->FranchiseTax);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->FranchiseTax = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // FranchiseTaxOthers
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->FranchiseTaxOthers);
            $existingValue = BillMirror::doubleNull($billMirror->FranchiseTaxOthers);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->FranchiseTaxOthers = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // BusinessTax
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->BusinessTax);
            $existingValue = BillMirror::doubleNull($billMirror->BusinessTax);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->BusinessTax = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // RealPropertyTax
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->RealPropertyTax);
            $existingValue = BillMirror::doubleNull($billMirror->RealPropertyTax);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->RealPropertyTax = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // InterClassCrossSubsidyCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->InterClassCrossSubsidyCharge);
            $existingValue = BillMirror::doubleNull($billMirror->InterClassCrossSubsidyCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->InterClassCrossSubsidyCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // PowerActReduction
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->PowerActReduction);
            $existingValue = BillMirror::doubleNull($billMirror->PowerActReduction);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->PowerActReduction = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // SeniorCitizenSubsidy
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->SeniorCitizenSubsidy);
            $existingValue = BillMirror::doubleNull($billMirror->SeniorCitizenSubsidy);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->SeniorCitizenSubsidy = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // EnvironmentalCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->EnvironmentalCharge);
            $existingValue = BillMirror::doubleNull($billMirror->EnvironmentalCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->EnvironmentalCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // StrandedContractCosts
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->StrandedContractCosts);
            $existingValue = BillMirror::doubleNull($billMirror->StrandedContractCosts);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->StrandedContractCosts = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // NPCStrandedDebt
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->NPCStrandedDebt);
            $existingValue = BillMirror::doubleNull($billMirror->NPCStrandedDebt);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->NPCStrandedDebt = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // FeedInTariffAllowance
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->FeedInTariffAllowance);
            $existingValue = BillMirror::doubleNull($billMirror->FeedInTariffAllowance);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->FeedInTariffAllowance = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // MissionaryElectrificationREDCI
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->MissionaryElectrificationREDCI);
            $existingValue = BillMirror::doubleNull($billMirror->MissionaryElectrificationREDCI);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->MissionaryElectrificationREDCI = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // MissionaryElectrificationSPUG
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->MissionaryElectrificationSPUG);
            $existingValue = BillMirror::doubleNull($billMirror->MissionaryElectrificationSPUG);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->MissionaryElectrificationSPUG = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // MissionaryElectrificationSPUGTRUEUP
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->MissionaryElectrificationSPUGTRUEUP);
            $existingValue = BillMirror::doubleNull($billMirror->MissionaryElectrificationSPUGTRUEUP);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->MissionaryElectrificationSPUGTRUEUP = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // GenerationVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->GenerationVAT);
            $existingValue = BillMirror::doubleNull($billMirror->GenerationVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->GenerationVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // ACRMVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->ACRMVAT);
            $existingValue = BillMirror::doubleNull($billMirror->ACRMVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->ACRMVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // TransmissionVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->TransmissionVAT);
            $existingValue = BillMirror::doubleNull($billMirror->TransmissionVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->TransmissionVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // SystemLossVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->SystemLossVAT);
            $existingValue = BillMirror::doubleNull($billMirror->SystemLossVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->SystemLossVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // DistributionVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->DistributionVAT);
            $existingValue = BillMirror::doubleNull($billMirror->DistributionVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->DistributionVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // OthersVAT
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->OthersVAT);
            $existingValue = BillMirror::doubleNull($billMirror->OthersVAT);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->OthersVAT = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        return $amount;
    }

    public static function populateBilledAmountUpdate($amount, $bill, $billMirror) {
        // GENERATION SYSTEM
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->GenerationSystemCharge);
            $existingValue = BillMirror::doubleNull($billMirror->GenerationSystemCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->GenerationSystemCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // ACRM
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->ACRM);
            $existingValue = BillMirror::doubleNull($billMirror->ACRM);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->ACRM = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // TransmissionDeliveryChargeKWH
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->TransmissionDeliveryChargeKWH);
            $existingValue = BillMirror::doubleNull($billMirror->TransmissionDeliveryChargeKWH);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->TransmissionDeliveryChargeKWH = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // SystemLossCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->SystemLossCharge);
            $existingValue = BillMirror::doubleNull($billMirror->SystemLossCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->SystemLossCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // DistributionDemandCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->DistributionDemandCharge);
            $existingValue = BillMirror::doubleNull($billMirror->DistributionDemandCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->DistributionDemandCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // DistributionSystemCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->DistributionSystemCharge);
            $existingValue = BillMirror::doubleNull($billMirror->DistributionSystemCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->DistributionSystemCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // SupplyRetailCustomerCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->SupplyRetailCustomerCharge);
            $existingValue = BillMirror::doubleNull($billMirror->SupplyRetailCustomerCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->SupplyRetailCustomerCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // SupplySystemCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->SupplySystemCharge);
            $existingValue = BillMirror::doubleNull($billMirror->SupplySystemCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->SupplySystemCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // MeteringSystemCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->MeteringSystemCharge);
            $existingValue = BillMirror::doubleNull($billMirror->MeteringSystemCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->MeteringSystemCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // MeteringRetailCustomerCharge
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->MeteringRetailCustomerCharge);
            $existingValue = BillMirror::doubleNull($billMirror->MeteringRetailCustomerCharge);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->MeteringRetailCustomerCharge = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        // LifelineRate
        if ($amount > 0) {
            $dist =  BillMirror::doubleNull($bill->LifelineRate);
            $existingValue = BillMirror::doubleNull($billMirror->LifelineRate);
            if ($existingValue == $dist) {

            } else {
                $amnt = BillMirror::distributeUpdate($amount, $dist, $existingValue);
                $billMirror->LifelineRate = ($amnt + $existingValue);
                $amount = $amount - $amnt;
            }
        }

        return $amount;
    }

    public static function doubleNull($value) {
        return $value != null ? floatval($value) : 0;
    }

    public static function bridgeFromBills($bill) {
        $billMirror = new BillMirror;
            
        $billMirror->BillNumber = $bill->BillNumber;
        $billMirror->AccountNumber = $bill->AccountNumber;
        $billMirror->ServicePeriod = $bill->ServicePeriod;
        $billMirror->AdditionalCharges = $bill->AdditionalCharges;
        $billMirror->Deductions = $bill->Deductions;
        $billMirror->NetAmount = $bill->NetAmount;
        $billMirror->BillingDate = $bill->BillingDate;
        $billMirror->ServiceDateFrom = $bill->ServiceDateFrom;
        $billMirror->ServiceDateTo = $bill->ServiceDateTo;
        $billMirror->DueDate = $bill->DueDate;
        $billMirror->GenerationSystemCharge = $bill->GenerationSystemCharge;
        $billMirror->TransmissionDeliveryChargeKW = $bill->TransmissionDeliveryChargeKW;
        $billMirror->TransmissionDeliveryChargeKWH = $bill->TransmissionDeliveryChargeKWH;
        $billMirror->SystemLossCharge = $bill->SystemLossCharge;
        $billMirror->DistributionDemandCharge = $bill->DistributionDemandCharge;
        $billMirror->DistributionSystemCharge = $bill->DistributionSystemCharge;
        $billMirror->SupplyRetailCustomerCharge = $bill->SupplyRetailCustomerCharge;
        $billMirror->SupplySystemCharge = $bill->SupplySystemCharge;
        $billMirror->MeteringRetailCustomerCharge = $bill->MeteringRetailCustomerCharge;
        $billMirror->MeteringSystemCharge = $bill->MeteringSystemCharge;
        $billMirror->RFSC = $bill->RFSC;
        $billMirror->LifelineRate = $bill->LifelineRate;
        $billMirror->InterClassCrossSubsidyCharge = $bill->InterClassCrossSubsidyCharge;
        $billMirror->PPARefund = $bill->PPARefund;
        $billMirror->SeniorCitizenSubsidy = $bill->SeniorCitizenSubsidy;
        $billMirror->MissionaryElectrificationCharge = $bill->MissionaryElectrificationCharge;
        $billMirror->EnvironmentalCharge = $bill->EnvironmentalCharge;
        $billMirror->StrandedContractCosts = $bill->StrandedContractCosts;
        $billMirror->NPCStrandedDebt = $bill->NPCStrandedDebt;
        $billMirror->FeedInTariffAllowance = $bill->FeedInTariffAllowance;
        $billMirror->MissionaryElectrificationREDCI = $bill->MissionaryElectrificationREDCI;
        $billMirror->GenerationVAT = $bill->GenerationVAT;
        $billMirror->TransmissionVAT = $bill->TransmissionVAT;
        $billMirror->SystemLossVAT = $bill->SystemLossVAT;
        $billMirror->DistributionVAT = $bill->DistributionVAT;
        $billMirror->RealPropertyTax = $bill->RealPropertyTax;
        $billMirror->Notes = $bill->Notes;
        $billMirror->UserId = $bill->UserId;
        $billMirror->OtherGenerationRateAdjustment = $bill->OtherGenerationRateAdjustment;
        $billMirror->OtherTransmissionCostAdjustmentKW = $bill->OtherTransmissionCostAdjustmentKW;
        $billMirror->OtherTransmissionCostAdjustmentKWH = $bill->OtherTransmissionCostAdjustmentKWH;
        $billMirror->OtherSystemLossCostAdjustment = $bill->OtherSystemLossCostAdjustment;
        $billMirror->OtherLifelineRateCostAdjustment = $bill->OtherLifelineRateCostAdjustment;
        $billMirror->SeniorCitizenDiscountAndSubsidyAdjustment = $bill->SeniorCitizenDiscountAndSubsidyAdjustment;
        $billMirror->FranchiseTax = $bill->FranchiseTax;
        $billMirror->BusinessTax = $bill->BusinessTax;
        $billMirror->AdjustmentType = $bill->AdjustmentType;
        $billMirror->Form2307Amount = $bill->Form2307Amount;
        $billMirror->DeductedDeposit = $bill->DeductedDeposit;
        $billMirror->ExcessDeposit = $bill->ExcessDeposit;
        $billMirror->IsUnlockedForPayment = $bill->IsUnlockedForPayment;
        $billMirror->UnlockedBy = $bill->UnlockedBy;
        $billMirror->Evat2Percent = $bill->Evat2Percent;
        $billMirror->Evat5Percent = $bill->Evat5Percent;
        $billMirror->AdjustmentNumber = $bill->AdjustmentNumber;
        $billMirror->AdjustedBy = $bill->AdjustedBy;
        $billMirror->DateAdjusted = $bill->DateAdjusted;
        $billMirror->ForCancellation = $bill->ForCancellation;
        $billMirror->CancelRequestedBy = $bill->CancelRequestedBy;
        $billMirror->CancelApprovedBy = $bill->CancelApprovedBy;
        $billMirror->KatasNgVat = $bill->KatasNgVat;
        $billMirror->SolarImportPresent = $bill->SolarImportPresent;
        $billMirror->SolarImportPrevious = $bill->SolarImportPrevious;
        $billMirror->SolarExportPresent = $bill->SolarExportPresent;
        $billMirror->SolarExportPrevious = $bill->SolarExportPrevious;
        $billMirror->SolarImportKwh = $bill->SolarImportKwh;
        $billMirror->SolarExportKwh = $bill->SolarExportKwh;
        $billMirror->GenerationChargeSolarExport = $bill->GenerationChargeSolarExport;
        $billMirror->SolarResidualCredit = $bill->SolarResidualCredit;
        $billMirror->SolarDemandChargeKW = $bill->SolarDemandChargeKW;
        $billMirror->SolarDemandChargeKWH = $bill->SolarDemandChargeKWH;
        $billMirror->SolarRetailCustomerCharge = $bill->SolarRetailCustomerCharge;
        $billMirror->SolarSupplySystemCharge = $bill->SolarSupplySystemCharge;
        $billMirror->SolarMeteringRetailCharge = $bill->SolarMeteringRetailCharge;
        $billMirror->SolarMeteringSystemCharge = $bill->SolarMeteringSystemCharge;
        $billMirror->Item1 = $bill->Item1;
        $billMirror->Item2 = $bill->Item2;
        $billMirror->Item3 = $bill->Item3;
        $billMirror->Item4 = $bill->Item4;
        $billMirror->Item5 = $bill->Item5;
        $billMirror->PaidAmount = $bill->PaidAmount;
        $billMirror->Balance = $bill->Balance;
        $billMirror->ACRM = $bill->ACRM;
        $billMirror->PowerActReduction = $bill->PowerActReduction;
        $billMirror->ACRMVAT = $bill->ACRMVAT;
        $billMirror->MissionaryElectrificationSPUG = $bill->MissionaryElectrificationSPUG;
        $billMirror->MissionaryElectrificationSPUGTRUEUP = $bill->MissionaryElectrificationSPUGTRUEUP;
        $billMirror->FranchiseTaxOthers = $bill->FranchiseTaxOthers;
        $billMirror->OthersVAT = $bill->OthersVAT;
        $billMirror->AdvancedMaterialDeposit = $bill->AdvancedMaterialDeposit;
        $billMirror->CustomerDeposit = $bill->CustomerDeposit;
        $billMirror->TransformerRental = $bill->TransformerRental;
        $billMirror->AdjustmentRequestedBy = $bill->AdjustmentRequestedBy;
        $billMirror->AdjustmentApprovedBy = $bill->AdjustmentApprovedBy;
        $billMirror->AdjustmentStatus = $bill->AdjustmentStatus;
        $billMirror->DateAdjustmentRequested = $bill->DateAdjustmentRequested;
        $billMirror->TermedPayments = $bill->TermedPayments;
            
        return $billMirror;
    }
}
