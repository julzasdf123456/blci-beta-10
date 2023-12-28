<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Rates;
use App\Models\Bills;
use App\Models\PaidBills;
use App\Models\IDGenerator;
use App\Models\ArrearsLedgerDistribution;
use App\Models\PrePaymentBalance;
use App\Models\PrePaymentTransHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Bills
 * @package App\Models
 * @version January 27, 2022, 2:09 pm PST
 *
 * @property string $BillNumber
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $Multiplier
 * @property string $Coreloss
 * @property string $KwhUsed
 * @property string $PreviousKwh
 * @property string $PresentKwh
 * @property string $DemandPreviousKwh
 * @property string $DemandPresentKwh
 * @property string $AdditionalKwh
 * @property string $AdditionalDemandKwh
 * @property string $KwhAmount
 * @property string $EffectiveRate
 * @property string $AdditionalCharges
 * @property string $Deductions
 * @property string $NetAmount
 * @property string $BillingDate
 * @property string $ServiceDateFrom
 * @property string $ServiceDateTo
 * @property string $DueDate
 * @property string $MeterNumber
 * @property string $ConsumerType
 * @property string $BillType
 * @property string $GenerationSystemCharge
 * @property string $TransmissionDeliveryChargeKW
 * @property string $TransmissionDeliveryChargeKWH
 * @property string $SystemLossCharge
 * @property string $DistributionDemandCharge
 * @property string $DistributionSystemCharge
 * @property string $SupplyRetailCustomerCharge
 * @property string $SupplySystemCharge
 * @property string $MeteringRetailCustomerCharge
 * @property string $MeteringSystemCharge
 * @property string $RFSC
 * @property string $LifelineRate
 * @property string $InterClassCrossSubsidyCharge
 * @property string $PPARefund
 * @property string $SeniorCitizenSubsidy
 * @property string $MissionaryElectrificationCharge
 * @property string $EnvironmentalCharge
 * @property string $StrandedContractCosts
 * @property string $NPCStrandedDebt
 * @property string $FeedInTariffAllowance
 * @property string $MissionaryElectrificationREDCI
 * @property string $GenerationVAT
 * @property string $TransmissionVAT
 * @property string $SystemLossVAT
 * @property string $DistributionVAT
 * @property string $RealPropertyTax
 * @property string $Notes
 * @property string $UserId
 * @property string $BilledFrom
 * @property string $KatasNgVat
 */
class Bills extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_Bills';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BillNumber',
        'AccountNumber',
        'ServicePeriod',
        'Multiplier',
        'Coreloss',
        'KwhUsed',
        'PreviousKwh',
        'PresentKwh',
        'DemandPreviousKwh',
        'DemandPresentKwh',
        'AdditionalKwh',
        'AdditionalDemandKwh',
        'KwhAmount',
        'EffectiveRate',
        'AdditionalCharges',
        'Deductions',
        'NetAmount',
        'BillingDate',
        'ServiceDateFrom',
        'ServiceDateTo',
        'DueDate',
        'MeterNumber',
        'ConsumerType',
        'BillType',
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
        'OthersVAT',
        'RealPropertyTax',
        'Notes',
        'UserId',
        'BilledFrom',
        'AveragedCount',
        'MergedToCollectible',
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
        'SolarResidualCredit', // IF NEGATIVE ANG AMOUNT
        'SolarDemandChargeKW',
        'SolarDemandChargeKWH',
        'SolarRetailCustomerCharge',
        'SolarSupplySystemCharge',
        'SolarMeteringRetailCharge',
        'SolarMeteringSystemCharge',
        'Item1', // CURRENT AMOUNT DU TO CUSTOMER / PARTIAL AMOUNT
        'Item2', 
        'Item3', // INDICATOR IF THE BILL IS APPROVED BY MANAGEMENT TO BE UNPAID IN THE CASHIERING APP (SKIP_AUTO=AUTO SKIPPED, SKIP_MANUAL=NOT AUTOMATICALLY SKIPPED BUT CAN BE SKIPPED)
        'Item4', // CURRENT AMOUNT CUSTOMER TO DU (Solar Gen - Residual sa Previous)
        'Item5',
        'PaidAmount',
        'Balance',
        'ACRM',
        'PowerActReduction',
        'ACRMVAT',
        'MissionaryElectrificationSPUG',
        'MissionaryElectrificationSPUGTRUEUP',
        'FranchiseTaxOthers',
        'AdvancedMaterialDeposit',
        'CustomerDeposit',
        'TransformerRental',
        'AdjustmentRequestedBy',
        'AdjustmentApprovedBy',
        'AdjustmentStatus', // PENDING ADJUSTMENT APPROVAL, PENDING CANCELLATION APPROVAL, ADJUSTMENT APPROVED, CANCELLATION APPROVED
        'DateAdjustmentRequested',
        'TermedPayments',
        'SurchargeWaived', // APPROVED, PENDING APPROVAL
        'SurchargeWaiveRequestedBy',
        'SurchargeWaiveApprovedBy',
        'SurchargeWaiveRequestDate',
        'SurchargeWaiveApprovedDate',
        'SMSSent',
        'EmailSent',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BillNumber' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'string',
        'Multiplier' => 'string',
        'Coreloss' => 'string',
        'KwhUsed' => 'string',
        'PreviousKwh' => 'string',
        'PresentKwh' => 'string',
        'DemandPreviousKwh' => 'string',
        'DemandPresentKwh' => 'string',
        'AdditionalKwh' => 'string',
        'AdditionalDemandKwh' => 'string',
        'KwhAmount' => 'string',
        'EffectiveRate' => 'string',
        'AdditionalCharges' => 'string',
        'Deductions' => 'string',
        'NetAmount' => 'string',
        'BillingDate' => 'string',
        'ServiceDateFrom' => 'string',
        'ServiceDateTo' => 'string',
        'DueDate' => 'string',
        'MeterNumber' => 'string',
        'ConsumerType' => 'string',
        'BillType' => 'string',
        'GenerationSystemCharge' => 'string',
        'TransmissionDeliveryChargeKW' => 'string',
        'TransmissionDeliveryChargeKWH' => 'string',
        'SystemLossCharge' => 'string',
        'DistributionDemandCharge' => 'string',
        'DistributionSystemCharge' => 'string',
        'SupplyRetailCustomerCharge' => 'string',
        'SupplySystemCharge' => 'string',
        'MeteringRetailCustomerCharge' => 'string',
        'MeteringSystemCharge' => 'string',
        'RFSC' => 'string',
        'LifelineRate' => 'string',
        'InterClassCrossSubsidyCharge' => 'string',
        'PPARefund' => 'string',
        'SeniorCitizenSubsidy' => 'string',
        'MissionaryElectrificationCharge' => 'string',
        'EnvironmentalCharge' => 'string',
        'StrandedContractCosts' => 'string',
        'NPCStrandedDebt' => 'string',
        'FeedInTariffAllowance' => 'string',
        'MissionaryElectrificationREDCI' => 'string',
        'GenerationVAT' => 'string',
        'TransmissionVAT' => 'string',
        'SystemLossVAT' => 'string',
        'DistributionVAT' => 'string',
        'OthersVAT' => 'string',
        'RealPropertyTax' => 'string',
        'Notes' => 'string',
        'UserId' => 'string',
        'BilledFrom' => 'string',
        'AveragedCount' => 'string',
        'MergedToCollectible' => 'string',
        'OtherGenerationRateAdjustment' => 'string',
        'OtherTransmissionCostAdjustmentKW' => 'string',
        'OtherTransmissionCostAdjustmentKWH' => 'string',
        'OtherSystemLossCostAdjustment' => 'string',
        'OtherLifelineRateCostAdjustment' => 'string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'string',
        'FranchiseTax' => 'string',
        'BusinessTax' => 'string',
        'AdjustmentType' => 'string',
        'Form2307Amount' => 'string',
        'DeductedDeposit' => 'string',
        'ExcessDeposit' => 'string',
        'IsUnlockedForPayment' => 'string',
        'UnlockedBy' => 'string',
        'Evat2Percent' => 'string',
        'Evat5Percent' => 'string',
        'AdjustmentNumber' => 'string',
        'AdjustedBy' => 'string',
        'DateAdjusted' => 'string',
        'ForCancellation' => 'string',
        'CancelRequestedBy' => 'string',
        'CancelApprovedBy' => 'string',
        'KatasNgVat' => 'string',
        'SolarImportPresent' => 'string',
        'SolarImportPrevious' => 'string',
        'SolarExportPresent' => 'string',
        'SolarExportPrevious' => 'string',
        'SolarImportKwh' => 'string',
        'SolarExportKwh' => 'string',
        'GenerationChargeSolarExport' => 'string',
        'SolarResidualCredit' => 'string',
        'SolarDemandChargeKW' => 'string',
        'SolarDemandChargeKWH' => 'string',
        'SolarRetailCustomerCharge' => 'string',
        'SolarSupplySystemCharge' => 'string',
        'SolarMeteringRetailCharge' => 'string',
        'SolarMeteringSystemCharge' => 'string',
        'Item1' => 'string',
        'Item2' => 'string',
        'Item3' => 'string',
        'Item4' => 'string',
        'Item5' => 'string',        
        'PaidAmount' => 'string',
        'Balance' => 'string',
        'ACRM' => 'string',
        'PowerActReduction' => 'string',
        'ACRMVAT' => 'string',
        'MissionaryElectrificationSPUG' => 'string',
        'MissionaryElectrificationSPUGTRUEUP' => 'string',
        'FranchiseTaxOthers' => 'string',
        'AdvancedMaterialDeposit' => 'string',
        'CustomerDeposit' => 'string',
        'TransformerRental' => 'string',
        'AdjustmentRequestedBy' => 'string',
        'AdjustmentApprovedBy' => 'string',
        'AdjustmentStatus' => 'string',
        'DateAdjustmentRequested' => 'string',
        'TermedPayments' => 'string',
        'SurchargeWaived' => 'string',
        'SurchargeWaiveRequestedBy' => 'string',
        'SurchargeWaiveApprovedBy' => 'string',
        'SurchargeWaiveRequestDate' => 'string',
        'SurchargeWaiveApprovedDate' => 'string',
        'SMSSent' => 'string',
        'EmailSent' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BillNumber' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'Multiplier' => 'nullable|string|max:255',
        'Coreloss' => 'nullable|string|max:255',
        'KwhUsed' => 'nullable|string|max:255',
        'PreviousKwh' => 'nullable|string|max:255',
        'PresentKwh' => 'nullable|string|max:255',
        'DemandPreviousKwh' => 'nullable|string|max:255',
        'DemandPresentKwh' => 'nullable|string|max:255',
        'AdditionalKwh' => 'nullable|string|max:255',
        'AdditionalDemandKwh' => 'nullable|string|max:255',
        'KwhAmount' => 'nullable|string|max:255',
        'EffectiveRate' => 'nullable|string|max:255',
        'AdditionalCharges' => 'nullable|string|max:255',
        'Deductions' => 'nullable|string|max:255',
        'NetAmount' => 'nullable|string|max:255',
        'BillingDate' => 'nullable',
        'ServiceDateFrom' => 'nullable',
        'ServiceDateTo' => 'nullable',
        'DueDate' => 'nullable',
        'MeterNumber' => 'nullable|string|max:255',
        'ConsumerType' => 'nullable|string|max:255',
        'BillType' => 'nullable|string|max:255',
        'GenerationSystemCharge' => 'nullable|string|max:20',
        'TransmissionDeliveryChargeKW' => 'nullable|string|max:20',
        'TransmissionDeliveryChargeKWH' => 'nullable|string|max:20',
        'SystemLossCharge' => 'nullable|string|max:20',
        'DistributionDemandCharge' => 'nullable|string|max:20',
        'DistributionSystemCharge' => 'nullable|string|max:20',
        'SupplyRetailCustomerCharge' => 'nullable|string|max:20',
        'SupplySystemCharge' => 'nullable|string|max:20',
        'MeteringRetailCustomerCharge' => 'nullable|string|max:20',
        'MeteringSystemCharge' => 'nullable|string|max:20',
        'RFSC' => 'nullable|string|max:20',
        'LifelineRate' => 'nullable|string|max:20',
        'InterClassCrossSubsidyCharge' => 'nullable|string|max:20',
        'PPARefund' => 'nullable|string|max:20',
        'SeniorCitizenSubsidy' => 'nullable|string|max:20',
        'MissionaryElectrificationCharge' => 'nullable|string|max:20',
        'EnvironmentalCharge' => 'nullable|string|max:20',
        'StrandedContractCosts' => 'nullable|string|max:20',
        'NPCStrandedDebt' => 'nullable|string|max:20',
        'FeedInTariffAllowance' => 'nullable|string|max:20',
        'MissionaryElectrificationREDCI' => 'nullable|string|max:20',
        'GenerationVAT' => 'nullable|string|max:20',
        'TransmissionVAT' => 'nullable|string|max:20',
        'SystemLossVAT' => 'nullable|string|max:20',
        'DistributionVAT' => 'nullable|string|max:20',
        'OthersVAT' => 'nullable|string',
        'RealPropertyTax' => 'nullable|string|max:20',
        'Notes' => 'nullable|string|max:2500',
        'UserId' => 'nullable|string|max:255',
        'BilledFrom' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AveragedCount' => 'nullable|string',
        'MergedToCollectible' => 'nullable|string',
        'OtherGenerationRateAdjustment' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKW' => 'nullable|string',
        'OtherTransmissionCostAdjustmentKWH' => 'nullable|string',
        'OtherSystemLossCostAdjustment' => 'nullable|string',
        'OtherLifelineRateCostAdjustment' => 'nullable|string',
        'SeniorCitizenDiscountAndSubsidyAdjustment' => 'nullable|string',
        'FranchiseTax' => 'nullable|string',
        'BusinessTax' => 'nullable|string',
        'AdjustmentType' => 'nullable|string',
        'Form2307Amount' => 'nullable|string',
        'DeductedDeposit' => 'nullable|string',
        'ExcessDeposit' => 'nullable|string',
        'IsUnlockedForPayment' => 'nullable|string',
        'UnlockedBy' => 'nullable|string',
        'Evat2Percent' => 'nullable|string',
        'Evat5Percent' => 'nullable|string',
        'AdjustmentNumber' => 'nullable|string',
        'AdjustedBy' => 'nullable|string',
        'DateAdjusted' => 'nullable|string',
        'ForCancellation' => 'nullable|string',
        'CancelRequestedBy' => 'nullable|string',
        'CancelApprovedBy' => 'nullable|string',
        'KatasNgVat' => 'nullable|string',
        'SolarImportPresent' => 'nullable|string',
        'SolarImportPrevious' => 'nullable|string',
        'SolarExportPresent' => 'nullable|string',
        'SolarExportPrevious' => 'nullable|string',
        'SolarImportKwh' => 'nullable|string',
        'SolarExportKwh' => 'nullable|string',
        'GenerationChargeSolarExport' => 'nullable|string',
        'SolarResidualCredit' => 'nullable|string',
        'SolarDemandChargeKW' => 'nullable|string',
        'SolarDemandChargeKWH' => 'nullable|string',
        'SolarRetailCustomerCharge' => 'nullable|string',
        'SolarSupplySystemCharge' => 'nullable|string',
        'SolarMeteringRetailCharge' => 'nullable|string',
        'SolarMeteringSystemCharge' => 'nullable|string',
        'Item1' => 'nullable|string',
        'Item2' => 'nullable|string',
        'Item3' => 'nullable|string',
        'Item4' => 'nullable|string',
        'Item5' => 'nullable|string',   
        'PaidAmount' => 'nullable|string',
        'Balance' => 'nullable|string',
        'ACRM' => 'nullable|string',
        'PowerActReduction' => 'nullable|string',
        'ACRMVAT' => 'nullable|string',
        'MissionaryElectrificationSPUG' => 'nullable|string',
        'MissionaryElectrificationSPUGTRUEUP' => 'nullable|string',
        'FranchiseTaxOthers' => 'nullable|string',
        'AdvancedMaterialDeposit' => 'nullable|string',
        'CustomerDeposit' => 'nullable|string',
        'TransformerRental' => 'nullable|string',
        'AdjustmentRequestedBy' => 'nullable|string',
        'AdjustmentApprovedBy' => 'nullable|string',
        'AdjustmentStatus' => 'nullable|string',
        'DateAdjustmentRequested' => 'nullable|string',
        'TermedPayments' => 'nullable|string',
        'SurchargeWaived' => 'nullable|string',
        'SurchargeWaiveRequestedBy' => 'nullable|string',
        'SurchargeWaiveApprovedBy' => 'nullable|string',
        'SurchargeWaiveRequestDate' => 'nullable|string',
        'SurchargeWaiveApprovedDate' => 'nullable|string',
        'SMSSent' => 'nullable|string',
        'EmailSent' => 'nullable|string',
    ];

    public static function getHighConsumptionPercentageAlert() {
        return .5;
    }

    public static function createDueDate($readDate) {
        return date('Y-m-d', strtotime($readDate . ' +10 days'));
    }

    public static function computePenalty($netAmount) {
        if ($netAmount > 1000) {
            return ($netAmount * .3) + $netAmount;
        } else {
            return 56.00 + $netAmount;
        }
    }

    public static function getPenalty($netAmount) {
        if ($netAmount > 1000) {
            return $netAmount * .3;
        } else {
            return 56.00;
        }
    }

    public static function getFinalPenalty($bill) {
        return (floatval($bill->Balance) * .0226);
    }

    public static function getFinalRawPenalty($bill) {
        return (floatval($bill->Balance) * .0226);
    }

    public static function getAccountType($account) {
        if ($account->AccountType == 'RESIDENTIAL RURAL' || $account->AccountType == 'RURAL RESIDENTIAL') {
            return 'RESIDENTIAL';
        } else {
            return $account->AccountType;
        }
    }

    public static function getAccountTypeByType($type) {
        if ($type == 'RESIDENTIAL RURAL' || $type == 'RURAL RESIDENTIAL') {
            return 'RESIDENTIAL';
        } else {
            return $type;
        }
    }

    public static function isHighVoltage($accountType) {
        if ($accountType == 'COMMERCIAL HIGH VOLTAGE' || $accountType == 'INDUSTRIAL HIGH VOLTAGE' || $accountType=='PUBLIC BUILDING HIGH VOLTAGE' || $accountType == 'COMMERCIAL' || $accountType == 'INDUSTRIAL' || $accountType=='PUBLIC BUILDING' || $accountType=='IRRIGATION/WATER SYSTEMS') {
            return true;
        } else {
            return false;
        }
    }

    public static function isPenaltyable($accountType) {
        // if ($accountType == 'RESIDENTIAL' || $accountType == 'PUBLIC BUILDING' || $accountType=='PUBLIC BUILDING HIGH VOLTAGE' || $accountType == 'STREET LIGHTS' || $accountType == 'IRRIGATION/WATER SYSTEMS') {
        //     return false;
        // } else {
        //     return true;
        // }
        return true;
    }

    public static function assessDueBillAndGetSurcharge($bill) {
        if (Bills::isPenaltyable(Bills::getAccountTypeByType($bill->ConsumerType))) {
            if ($bill->SurchargeWaived == 'APPROVED') {
                return 0;
            } else {
                return Bills::getFinalRawPenalty($bill) /* + Bills::getInterest($bill) */;   
            }          
        } else {                
            return 0;
        }  
    }

    public static function getInterest($bill) {
        // GET MONTHS PASSED
        // $d2 = date('Y-m-d');
        $newRate = Rates::select('ServicePeriod')->limit(1)->orderByDesc('ServicePeriod')->first();
        $d2 = $newRate != null ? date('Y-m-d', strtotime($newRate->ServicePeriod)) : date('Y-m-d');
        $d1 = $bill != null ? date('Y-m-d', strtotime($bill->ServicePeriod)) : date('Y-m-d');

        $ts1 = strtotime($d1);
        $ts2 = strtotime($d2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $months = (($year2 - $year1) * 12) + ($month2 - $month1);

        // GET INTEREST FIGURE (2%)
        $interest = $bill != null ? (is_numeric($bill->NetAmount) ? floatval($bill->NetAmount) * .02 : 0) : 0;

        if ($d2 == $d1) {
            return 0;
        } else {
            return round(($months-1) * $interest, 2); 
        }
        
        // return $interest;
    }

    public static function getSurchargeFinal($bill) {
        if (date('Y-m-d') > $bill->DueDate) {
            return Bills::assessDueBillAndGetSurcharge($bill);
        } else {
            return 0;
        }
    }

    public static function isBillDue($bill) {
        if (date('Y-m-d') > $bill->DueDate) {
            return true;
        } else {
            return false;
        }
    }

    public static function getInterestOnly($bill) {
        if (date('Y-m-d') > $bill->DueDate) {
            if (Bills::isPenaltyable(Bills::getAccountTypeByType($bill->ConsumerType))) {
                return Bills::getInterest($bill);                
            } else {                
                return 0;
            } 
        } else {
            return 0;
        }
    }

    public static function getSurchargeOnly($bill) {
        if (date('Y-m-d') > $bill->DueDate) {
            if (Bills::isPenaltyable(Bills::getAccountTypeByType($bill->ConsumerType))) {
                return Bills::getFinalRawPenalty($bill);                
            } else {                
                return 0;
            } 
        } else {
            return 0;
        }
    }

    public static function getServiceDateFrom($accountNumber, $readDate, $period) {
        $bill = Bills::where('AccountNumber', $accountNumber)
            ->where('ServicePeriod', date('Y-m-01', strtotime($period . ' -1 month')))
            // ->orderByDesc('ServicePeriod')
            ->first();

        if ($bill != null) {
            return $bill->ServiceDateTo;
        } else {
            return date('Y-m-d', strtotime($readDate . ' -1 month'));
        }
    }

    public static function computeNetAmount($bill) {
        $amount = 0.0;

        $amount = $bill->GenerationSystemCharge +
                $bill->TransmissionDeliveryChargeKW +
                $bill->ACRM +
                $bill->TransmissionDeliveryChargeKWH + 
                $bill->SystemLossCharge +
                $bill->DistributionDemandCharge + 
                $bill->DistributionSystemCharge + 
                $bill->SupplyRetailCustomerCharge + 
                $bill->SupplySystemCharge +
                $bill->MeteringRetailCustomerCharge + 
                $bill->MeteringSystemCharge + 
                $bill->RFSC + 
                $bill->LifelineRate + 
                $bill->InterClassCrossSubsidyCharge + 
                $bill->PPARefund + 
                $bill->PowerActReduction + 
                $bill->MissionaryElectrificationCharge + 
                $bill->EnvironmentalCharge + 
                $bill->StrandedContractCosts + 
                $bill->NPCStrandedDebt + 
                $bill->FeedInTariffAllowance + 
                $bill->MissionaryElectrificationREDCI + 
                $bill->MissionaryElectrificationSPUG + 
                $bill->MissionaryElectrificationSPUGTRUEUP + 
                $bill->GenerationVAT + 
                $bill->TransmissionVAT +
                $bill->SystemLossVAT +
                $bill->DistributionVAT + 
                $bill->OthersVAT + 
                $bill->RealPropertyTax +
                $bill->ACRMVAT +
                $bill->OtherGenerationRateAdjustment +
                $bill->OtherTransmissionCostAdjustmentKW +
                $bill->OtherTransmissionCostAdjustmentKWH +
                $bill->OtherSystemLossCostAdjustment +
                $bill->OtherLifelineRateCostAdjustment +
                $bill->FranchiseTax +
                $bill->FranchiseTaxOthers +
                $bill->BusinessTax +
                $bill->TermedPayments +
                $bill->AdditionalCharges -
                $bill->Deductions -
                floatval($bill->Evat2Percent) -
                floatval($bill->Evat5Percent);

        return round($amount, 2);
    }

    public static function getPartialNetAmountNetMetering($bill) {
        $amount = 0.0;

        $amount = $bill->GenerationSystemCharge +
                $bill->TransmissionDeliveryChargeKW +
                $bill->TransmissionDeliveryChargeKWH + 
                $bill->SystemLossCharge +
                floatval($bill->DistributionDemandCharge) + 
                $bill->DistributionSystemCharge + 
                $bill->SupplyRetailCustomerCharge + 
                $bill->SupplySystemCharge +
                $bill->MeteringRetailCustomerCharge + 
                $bill->MeteringSystemCharge + 
                $bill->RFSC + 
                $bill->LifelineRate + 
                $bill->InterClassCrossSubsidyCharge + 
                $bill->PPARefund + 
                $bill->SeniorCitizenSubsidy +
                $bill->MissionaryElectrificationCharge + 
                $bill->EnvironmentalCharge + 
                $bill->StrandedContractCosts + 
                $bill->NPCStrandedDebt + 
                $bill->FeedInTariffAllowance + 
                $bill->MissionaryElectrificationREDCI + 
                $bill->GenerationVAT + 
                $bill->TransmissionVAT +
                $bill->SystemLossVAT +
                $bill->DistributionVAT + 
                $bill->RealPropertyTax +
                $bill->OtherGenerationRateAdjustment +
                $bill->OtherTransmissionCostAdjustmentKW +
                $bill->OtherTransmissionCostAdjustmentKWH +
                $bill->OtherSystemLossCostAdjustment +
                $bill->OtherLifelineRateCostAdjustment +
                $bill->SeniorCitizenDiscountAndSubsidyAdjustment +
                $bill->FranchiseTax +
                $bill->BusinessTax +
                $bill->AdditionalCharges +
                $bill->SolarDemandChargeKW +
                $bill->SolarDemandChargeKWH +
                $bill->SolarRetailCustomerCharge +
                $bill->SolarSupplySystemCharge +
                $bill->SolarMeteringRetailCharge +
                $bill->SolarMeteringSystemCharge -
                $bill->Deductions -
                $bill->Evat2Percent -
                $bill->Evat5Percent;

        return round($amount, 2);
    }

    // MODIFY THIS
    public static function computeLifeLine($account, $bill, $rate) {
        $kwhUsed = floatval($bill->KwhUsed) /* * floatval($bill->Multiplier)*/;
        // MODIFY THIS
        $deductibles = $bill->GenerationSystemCharge +
            $bill->ACRM +
            $bill->TransmissionDeliveryChargeKWH +
            // $bill->TransmissionDeliveryChargeKW +
            $bill->SystemLossCharge +
            // $bill->OtherGenerationRateAdjustment +
            // $bill->OtherTransmissionCostAdjustmentKW +
            // $bill->OtherTransmissionCostAdjustmentKWH +
            // $bill->OtherSystemLossCostAdjustment +
            $bill->DistributionDemandCharge +
            $bill->DistributionSystemCharge +
            $bill->SupplyRetailCustomerCharge +
            $bill->SupplySystemCharge +
            $bill->MeteringSystemCharge +
            $bill->MeteringRetailCustomerCharge;

        if ($account->Lifeliner == 'Yes') {
            if ($kwhUsed <= 20) {
                return -($deductibles * 1);
            } elseif ($kwhUsed > 20 && $kwhUsed <= 35) {
                return -($deductibles * .5);
            } elseif ($kwhUsed > 35 && $kwhUsed <= 55) {
                return -($deductibles * .4);
            } elseif ($kwhUsed > 55 && $kwhUsed <= 65) {
                return -($deductibles * .3);
            } elseif ($kwhUsed > 65 && $kwhUsed <= 75) {
                return -($deductibles * .2);
            } elseif ($kwhUsed > 75) {
                return $kwhUsed * Rates::floatRate($rate->LifelineRate);
            }    
        } else {
            return $kwhUsed * Rates::floatRate($rate->LifelineRate);
        }         
    }

    // MODIFY HIS
    public static function computeSeniorCitizen($account, $bill, $rate) {
        $kwhUsed = floatval($bill->KwhUsed) /* * floatval($bill->Multiplier)*/;
        // MODIFY THIS
        $netAmount = Bills::getBilledAmount($bill);

        if ($account->SeniorCitizen == 'Yes' && $kwhUsed <= 100) {
            return -($netAmount * .05);
        } else {
            if ($account->AccountType == 'RS' | $account->AccountType == 'RP') {
                return $kwhUsed * Rates::floatRate($rate->SeniorCitizenSubsidy);
            } else {
                return 0;
            }            
        }
    }

    public static function getBilledAmount($bill) {
        $billed = $bill->GenerationSystemCharge +
            $bill->ACRM +
            $bill->TransmissionDeliveryChargeKWH +
            // $bill->TransmissionDeliveryChargeKW +
            $bill->SystemLossCharge +
            // $bill->OtherGenerationRateAdjustment +
            // $bill->OtherTransmissionCostAdjustmentKW +
            // $bill->OtherTransmissionCostAdjustmentKWH +
            // $bill->OtherSystemLossCostAdjustment +
            $bill->DistributionDemandCharge +
            $bill->DistributionSystemCharge +
            $bill->SupplyRetailCustomerCharge +
            $bill->SupplySystemCharge +
            $bill->MeteringSystemCharge +
            $bill->MeteringRetailCustomerCharge +
            $bill->LifelineRate;

        return $billed;
    }

    public static function getOthersAmount($bill) {
        $others = $bill->FranchiseTax +
            $bill->FranchiseTaxOthers +
            $bill->BusinessTax +
            $bill->RealPropertyTax +
            $bill->InterClassCrossSubsidyCharge +
            $bill->PowerActReduction +
            $bill->SeniorCitizenSubsidy +
            $bill->EnvironmentalCharge +
            $bill->StrandedContractCosts +
            $bill->NPCStrandedDebt +
            $bill->FeedInTariffAllowance +
            $bill->MissionaryElectrificationREDCI +
            $bill->MissionaryElectrificationSPUG +
            $bill->MissionaryElectrificationSPUGTRUEUP +
            $bill->GenerationVAT +
            $bill->ACRMVAT +
            $bill->TransmissionVAT +
            $bill->SystemLossVAT +
            $bill->DistributionVAT +
            $bill->OthersVAT;

        return $others;
    }

    public static function get2307($bill) {
        $taxables = $bill->GenerationVAT +
            $bill->TransmissionVAT +
            $bill->SystemLossVAT +
            $bill->DistributionVAT +
            $bill->FranchiseTax +
            $bill->RealPropertyTax +
            $bill->BusinessTax;

        return round($taxables * (2/12), 2);
    }

    public static function getDistributionVat($bill) {
        return Bills::getDistributionTotal($bill) * .12;
    }

    public static function getDistributionTotal($bill) {
        $total = $bill->DistributionSystemCharge +
            $bill->DistributionDemandCharge +
            $bill->SupplyRetailCustomerCharge +
            $bill->MeteringRetailCustomerCharge +
            $bill->MeteringSystemCharge +
            $bill->LifelineRate;

        return $total;
    }

    public static function getMaterialDeposit($account, $bill) {
        if ($account->AdvancedMaterialDepositStatus == 'DEDUCTING') {
            if ($account->AdvancedMaterialDeposit > 0) {
                if (date('Y', strtotime($account->ConnectionDate)) < 2023) {
                    // 2022 and below
                    $deposit = round(Bills::getBilledAmount($bill) * .75, 2);

                    if ($deposit >= $account->AdvancedMaterialDeposit) {
                        return -$account->AdvancedMaterialDeposit;
                    } else {
                        return -$deposit;
                    }
                } else {
                    // 2023 above
                    $total = $bill->DistributionSystemCharge +
                        $bill->DistributionDemandCharge +
                        $bill->SupplyRetailCustomerCharge +
                        $bill->MeteringRetailCustomerCharge +
                        $bill->MeteringSystemCharge;

                    $deposit = round($total * .25, 2);

                    if ($deposit >= $account->AdvancedMaterialDeposit) {
                        return -$account->AdvancedMaterialDeposit;
                    } else {
                        return -$deposit;
                    }
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }        
    }

    public static function getCustomerDeposit($account, $bill) {
        if ($account->CustomerDepositStatus == 'DEDUCTING') {
            if ($account->CustomerDeposit > 0) {
                $billedAmt = Bills::getBilledAmount($bill);

                if ($account->CustomerDeposit > $billedAmt) {
                    return -round($billedAmt, 2);
                } else {
                    return -round($account->CustomerDeposit, 2);
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }        
    }

    /**
     * COMPUTES THE BILL AND SAVE
     * WITH A BOOLEAN PARAMETER TO SAVE OR NOT
     */
    public static function getRegularBill($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307, $isSave) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        /**
         * GET SURCHARGES
         */
        $unpaid = DB::table('Billing_Bills')
            ->whereRaw("AccountNumber='" . $account->id . "' AND Balance > 0 AND DueDate < GETDATE() AND ServicePeriod < '" . $period . "'")
            ->get();
        $previousSurcharges = 0;
        foreach($unpaid as $item) {
            $previousSurcharges += round(floatval(Bills::assessDueBillAndGetSurcharge($item)), 2);
        }
        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->get();
        $termedPaymentAmnt = 0;
        foreach($ocl as $item) {
            $termedPaymentAmnt += round(floatval($item->Amount), 2);
        }
         
        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $additionalCharges = 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $account->MeterDetailsId;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->ACRM = round($kwh * Rates::floatRate($rate->ACRM), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = $kwh == 0 ? 0 : round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->PowerActReduction = round($kwh * Rates::floatRate($rate->PowerActReduction), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->MissionaryElectrificationSPUG = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUG), 2);
                    $bill->MissionaryElectrificationSPUGTRUEUP = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUGTRUEUP), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    $bill->ACRMVAT = round($kwh * Rates::floatRate($rate->ACRMVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }                    
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    // if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    // } else {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    // }                    
                    
                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    $bill->FranchiseTax = round((Bills::getDistributionTotal($bill)) * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->FranchiseTaxOthers = round($previousSurcharges * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->OthersVAT = round(($bill->FranchiseTax + $bill->FranchiseTaxOthers + $previousSurcharges) * .12, 2);

                    $bill->TermedPayments = $termedPaymentAmnt;

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    // DEPOSITS
                    $bill->AdvancedMaterialDeposit = Bills::getMaterialDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->AdvancedMaterialDeposit;
                    $bill->CustomerDeposit = Bills::getCustomerDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->CustomerDeposit;

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    //RECOMPUTE NET AMOUNT AFTER SENIOR CITIZEN
                    $bill->NetAmount = round(floatval($bill->NetAmount) + floatval($bill->SeniorCitizenSubsidy), 2);

                    // COMPUTE BALANCE
                    if ($bill->PaidAmount > 0) {
                        $bal = $bill->Balance;
                        $net = $bill->NetAmount - $bal;

                        if ($net > 0) {
                            $bal = $net;
                        } else {
                            $addPrepayment = $bal - $bill->NetAmount;
                            // IPASULOD SA PREPAYMENT ANG SOBRA NGA NABAYAD NIYA
                            $prepBal = floatval($prepaymentBalance->Balance);

                            $prepBal = $prepBal + $addPrepayment;

                            $prepaymentBalance->Balance = round($prepBal, 2);
                            $prepaymentBalance->save();

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $addPrepayment;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();
                        }
                    } else {
                        $bill->Balance = $bill->NetAmount;
                    }                    

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    if ($isSave) {
                        $bill->save();

                    } 
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $account->MeterDetailsId;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->ACRM = round($kwh * Rates::floatRate($rate->ACRM), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = $kwh == 0 ? 0 : round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->PowerActReduction = round($kwh * Rates::floatRate($rate->PowerActReduction), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->MissionaryElectrificationSPUG = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUG), 2);
                    $bill->MissionaryElectrificationSPUGTRUEUP = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUGTRUEUP), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    $bill->ACRMVAT = round($kwh * Rates::floatRate($rate->ACRMVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }                    
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    // if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    // } else {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    // }                    
                    
                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    $bill->FranchiseTax = round((Bills::getDistributionTotal($bill)) * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->FranchiseTaxOthers = round($previousSurcharges * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);
                    
                    $bill->OthersVAT = round(($bill->FranchiseTax + $bill->FranchiseTaxOthers + $previousSurcharges) * .12, 2);

                    $bill->TermedPayments = $termedPaymentAmnt;

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    // DEPOSITS
                    $bill->AdvancedMaterialDeposit = Bills::getMaterialDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->AdvancedMaterialDeposit;
                    $bill->CustomerDeposit = Bills::getCustomerDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->CustomerDeposit;

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    //RECOMPUTE NET AMOUNT AFTER SENIOR CITIZEN
                    $bill->NetAmount = round(floatval($bill->NetAmount) + floatval($bill->SeniorCitizenSubsidy), 2);

                    // COMPUTE BALANCE
                    if ($bill->PaidAmount > 0) {
                        $bal = $bill->Balance;
                        $net = $bill->NetAmount - $bal;

                        if ($net > 0) {
                            $bal = $net;
                        } else {
                            $addPrepayment = $bal - $bill->NetAmount;
                            // IPASULOD SA PREPAYMENT ANG SOBRA NGA NABAYAD NIYA
                            $prepBal = floatval($prepaymentBalance->Balance);

                            $prepBal = $prepBal + $addPrepayment;

                            $prepaymentBalance->Balance = round($prepBal, 2);
                            $prepaymentBalance->save();

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $addPrepayment;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();
                        }
                    } else {
                        $bill->Balance = $bill->NetAmount;
                    }     

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    if ($isSave) {
                        $bill->save();

                    } 
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->PreviousKwh = ($prev == 0 ? 0 : round(floatval($prev), 2));
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = ($account->MeterDetailsId);
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->ACRM = round($kwh * Rates::floatRate($rate->ACRM), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = $kwh <= 0 ? 0 : round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->PowerActReduction = round($kwh * Rates::floatRate($rate->PowerActReduction), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->MissionaryElectrificationSPUG = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUG), 2);
                    $bill->MissionaryElectrificationSPUGTRUEUP = round($kwh * Rates::floatRate($rate->MissionaryElectrificationSPUGTRUEUP), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    $bill->ACRMVAT = round($kwh * Rates::floatRate($rate->ACRMVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }                    
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    // if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    // } else {
                    //     $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    // }                    
                    
                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    $bill->FranchiseTax = round((Bills::getDistributionTotal($bill)) * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->FranchiseTaxOthers = round($previousSurcharges * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);
                    
                    $bill->OthersVAT = round(($bill->FranchiseTax + $bill->FranchiseTaxOthers + $previousSurcharges) * .12, 2);

                    $bill->TermedPayments = $termedPaymentAmnt;

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    // DEPOSITS
                    $bill->AdvancedMaterialDeposit = Bills::getMaterialDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->AdvancedMaterialDeposit;
                    $bill->CustomerDeposit = Bills::getCustomerDeposit($account, $bill);
                    $bill->NetAmount = $bill->NetAmount + $bill->CustomerDeposit;

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    //RECOMPUTE NET AMOUNT AFTER SENIOR CITIZEN
                    $bill->NetAmount = round(floatval($bill->NetAmount) + floatval($bill->SeniorCitizenSubsidy), 2);

                    // COMPUTE BALANCE
                    if ($bill->PaidAmount > 0) {
                        $bal = $bill->Balance;
                        $net = $bill->NetAmount - $bal;

                        if ($net > 0) {
                            $bal = $net;
                        } else {
                            $addPrepayment = $bal - $bill->NetAmount;
                            // IPASULOD SA PREPAYMENT ANG SOBRA NGA NABAYAD NIYA
                            $prepBal = floatval($prepaymentBalance->Balance);

                            $prepBal = $prepBal + $addPrepayment;

                            $prepaymentBalance->Balance = round($prepBal, 2);
                            $prepaymentBalance->save();

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $addPrepayment;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();
                        }
                    } else {
                        $bill->Balance = $bill->NetAmount;
                    }     

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    if ($isSave) {
                        $bill->save();
                        
                    } 
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * COMPUTES THE BILL AND SAVE
     */
    public static function computeRegularBill($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();
         
        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }                    
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->PreviousKwh = ($prev == 0 ? 0 : round(floatval($prev), 2));
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->BillingDate = date('Y-m-d');
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = ($meter != null ? $meter->SerialNumber : null);
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round($excess, 2) . '';
                            $prepaymentBalance->save();

                            // INSERT TO PAID BILLS
                            $paidBills = new PaidBills;
                            $paidBills->id = IDGenerator::generateIDandRandString();
                            $paidBills->BillNumber = $bill->BillNumber;
                            $paidBills->AccountNumber = $bill->AccountNumber;
                            $paidBills->ServicePeriod = $bill->ServicePeriod;
                            $paidBills->ORNumber = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->ORNumber : IDGenerator::generateID();
                            $paidBills->ORDate = $latestPrepaymentHistory !=null ? date('Y-m-d', strtotime($latestPrepaymentHistory->created_at)) : date('Y-m-d');
                            $paidBills->KwhUsed = $bill->KwhUsed;
                            $paidBills->Teller = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->UserId : Auth::id();
                            $paidBills->OfficeTransacted = env('APP_LOCATION');
                            $paidBills->PostingDate = date('Y-m-d');
                            $paidBills->PostingTime = date('H:i:s');
                            $paidBills->Surcharge = 0;
                            $paidBills->Deductions = $bill->DeductedDeposit;
                            $paidBills->NetAmount = "0";
                            $paidBills->Source = 'MONTHLY BILL - Pre-Payments';
                            $paidBills->ObjectSourceId = $bill->id;
                            $paidBills->UserId = Auth::id();
                            $paidBills->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = '0';
                            $prepaymentBalance->save();
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * COMPUTES THE BILL ONLY
     */
    public static function computeRegularBillAndDontSave($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->PreviousKwh = $prev;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->BillingDate = $readDate;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = 0;
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = 0;
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = 0;
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * 2%
     */
    public static function add2Percent($billId) {
        $bill = Bills::find($billId);

        $percentage = floatval($bill->NetAmount) * .02;

        $bill->Evat2Percent = $percentage;
        $bill->NetAmount = round(floatval($bill->NetAmount) + $percentage, 2);
        $bill->save();

        return $bill;
    }

    public static function remove2Percent($billId) {
        $bill = Bills::find($billId);
        
        $bill->NetAmount = round(floatval($bill->NetAmount) - floatval($bill->Evat2Percent), 2);
        $bill->Evat2Percent = null;
        $bill->save();

        return $bill;
    }

    /**
     * 5%
     */
    public static function add5Percent($billId) {
        $bill = Bills::find($billId);

        $percentage = floatval($bill->NetAmount) * .05;

        $bill->Evat5Percent = $percentage;
        $bill->NetAmount = round(floatval($bill->NetAmount) + $percentage, 2);
        $bill->save();

        return $bill;
    }

    public static function remove5Percent($billId) {
        $bill = Bills::find($billId);
        
        $bill->NetAmount = round(floatval($bill->NetAmount) - floatval($bill->Evat5Percent), 2);
        $bill->Evat5Percent = null;
        $bill->save();

        return $bill;
    }

    public static function getFivePercent($item) {
        $subTotal = (floatval($item->DistributionSystemCharge) + 
            floatval($item->DistributionDemandCharge) +
            floatval($item->SupplyRetailCustomerCharge) + 
            floatval($item->MeteringRetailCustomerCharge) + 
            floatval($item->MeteringSystemCharge) + 
            floatval($item->LifelineRate) + 
            floatval($item->OtherLifelineRateCostAdjustment) + 
            floatval($item->InterClassCrossSubsidyCharge));
        
        $subTotal = $subTotal/.12;

        return round($subTotal * .05, 2);
    }
    
    public static function getTwoPercent($item) {
        $subTotal = (floatval($item->DistributionSystemCharge) + 
            floatval($item->DistributionDemandCharge) +
            floatval($item->SupplyRetailCustomerCharge) + 
            floatval($item->MeteringRetailCustomerCharge) + 
            floatval($item->MeteringSystemCharge) + 
            floatval($item->LifelineRate) + 
            floatval($item->OtherLifelineRateCostAdjustment) + 
            floatval($item->InterClassCrossSubsidyCharge) + 
            floatval($item->GenerationSystemCharge) + 
            floatval($item->TransmissionDeliveryChargeKWH) + 
            floatval($item->SystemLossCharge) + 
            floatval($item->ACRM));

        return round($subTotal * .02, 2);
    }

    /**
     * COMPUTES THE HIGH VOLTAGE BILL ONLY
     */
    public static function computeHighVoltageBillAndDontSave($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307, $demand) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $demand = round(floatval($demand), 2);
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->PreviousKwh = $prev;
                    $bill->BillingDate = $readDate;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * COMPUTES THE HIGH VOLTAGE AND SAVE TO DB
     */
    public static function computeHighVoltageBill($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307, $demand) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $demand = round(floatval($demand), 2);
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->PreviousKwh = $prev;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->BillingDate = $readDate;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round($excess, 2) . '';
                            $prepaymentBalance->save();

                            // INSERT TO PAID BILLS
                            $paidBills = new PaidBills;
                            $paidBills->id = IDGenerator::generateIDandRandString();
                            $paidBills->BillNumber = $bill->BillNumber;
                            $paidBills->AccountNumber = $bill->AccountNumber;
                            $paidBills->ServicePeriod = $bill->ServicePeriod;
                            $paidBills->ORNumber = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->ORNumber : IDGenerator::generateID();
                            $paidBills->ORDate = $latestPrepaymentHistory !=null ? date('Y-m-d', strtotime($latestPrepaymentHistory->created_at)) : date('Y-m-d');
                            $paidBills->KwhUsed = $bill->KwhUsed;
                            $paidBills->Teller = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->UserId : Auth::id();
                            $paidBills->OfficeTransacted = env('APP_LOCATION');
                            $paidBills->PostingDate = date('Y-m-d');
                            $paidBills->PostingTime = date('H:i:s');
                            $paidBills->Surcharge = 0;
                            $paidBills->Deductions = $bill->DeductedDeposit;
                            $paidBills->NetAmount = "0";
                            $paidBills->Source = 'MONTHLY BILL - Pre-Payments';
                            $paidBills->ObjectSourceId = $bill->id;
                            $paidBills->UserId = Auth::id();
                            $paidBills->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = '0';
                            $prepaymentBalance->save();
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * COMPUTES NET METERED BILLS
     */
    public static function computeNetMeteringBillAndDontSave($account, $billId, $kwh, $prev, $pres, $exportKwh, $exportPrev, $exportPres, $period, $readDate, $additionalCharges, $deductions, $is2307, $demand) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();
        
        $prevBill = Bills::where('ServicePeriod', date('Y-m-01', strtotime($period . ' -1 month')))
            ->where('AccountNumber', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $demand = round(floatval($demand), 2);
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);
            $exportKwh = round(floatval($exportKwh), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    // $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    /**
                     * NET METERING START
                     */
                    $bill->SolarExportPrevious = $exportPrev;
                    $bill->SolarExportPresent = $exportPres;
                    $bill->SolarExportKwh = $exportKwh;
                    $bill->SolarDemandChargeKW = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->SolarDemandChargeKWH = round($exportKwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SolarRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SolarSupplySystemCharge = round($exportKwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->SolarMeteringRetailCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->SolarMeteringSystemCharge = round($exportKwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->GenerationChargeSolarExport = round($exportKwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    /**
                     * NET METERING END
                     */

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    /**
                     * NET METERING START
                     */
                    $bill->Item1 = Bills::getPartialNetAmountNetMetering($bill);
                    $bill->Item4 = Bills::getSolarGenPlusResidual($prevBill, $bill);
                    $bill->NetAmount = Bills::getNetMeteringNetAmount($bill);

                    if (floatval($bill->NetAmount) < 0) {
                        $bill->SolarResidualCredit = round((floatval($bill->NetAmount) * -1), 2);
                    }
                    /**
                     * NET METERING END
                     */

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    // $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    /**
                     * NET METERING START
                     */
                    $bill->SolarExportPrevious = $exportPrev;
                    $bill->SolarExportPresent = $exportPres;
                    $bill->SolarExportKwh = $exportKwh;
                    $bill->SolarDemandChargeKW = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->SolarDemandChargeKWH = round($exportKwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SolarRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SolarSupplySystemCharge = round($exportKwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->SolarMeteringRetailCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->SolarMeteringSystemCharge = round($exportKwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->GenerationChargeSolarExport = round($exportKwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    /**
                     * NET METERING END
                     */
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    /**
                     * NET METERING START
                     */
                    $bill->Item1 = Bills::getPartialNetAmountNetMetering($bill);
                    $bill->Item4 = Bills::getSolarGenPlusResidual($prevBill, $bill);
                    $bill->NetAmount = Bills::getNetMeteringNetAmount($bill);

                    if (floatval($bill->NetAmount) < 0) {
                        $bill->SolarResidualCredit = round((floatval($bill->NetAmount) * -1), 2);
                    }
                    /**
                     * NET METERING END
                     */

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->PreviousKwh = $prev;
                    $bill->BillingDate = $readDate;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = round($kwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    // $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = round($kwh * Rates::floatRate($rate->FeedInTariffAllowance), 2);
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    /**
                     * NET METERING START
                     */
                    $bill->SolarExportPrevious = $exportPrev;
                    $bill->SolarExportPresent = $exportPres;
                    $bill->SolarExportKwh = $exportKwh;
                    $bill->SolarDemandChargeKW = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->SolarDemandChargeKWH = round($exportKwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SolarRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SolarSupplySystemCharge = round($exportKwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->SolarMeteringRetailCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->SolarMeteringSystemCharge = round($exportKwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->GenerationChargeSolarExport = round($exportKwh * Rates::floatRate($rate->GenerationSystemCharge), 2);
                    /**
                     * NET METERING END
                     */
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    /**
                     * NET METERING START
                     */
                    $bill->Item1 = Bills::getPartialNetAmountNetMetering($bill);
                    $bill->Item4 = Bills::getSolarGenPlusResidual($prevBill, $bill);
                    $bill->NetAmount = Bills::getNetMeteringNetAmount($bill);

                    if (floatval($bill->NetAmount) < 0) {
                        $bill->SolarResidualCredit = round((floatval($bill->NetAmount) * -1), 2);
                    }
                    /**
                     * NET METERING END
                     */

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    public static function getSolarGenPlusResidual($prevBill, $currBill) {
        $residual = ($prevBill != null ? ($prevBill->SolarResidualCredit != null && is_numeric($prevBill->SolarResidualCredit) ? floatval($prevBill->SolarResidualCredit) : 0) : 0);
        $solarGen = ($currBill != null ? ($currBill->GenerationChargeSolarExport != null && is_numeric($currBill->GenerationChargeSolarExport) ? floatval($currBill->GenerationChargeSolarExport) : 0) : 0);

        return round($residual + $solarGen, 2);
    }

    public static function getNetMeteringNetAmount($bill) {
        $partialAmt = ($bill != null ? ($bill->Item1 != null && is_numeric($bill->Item1) ? floatval($bill->Item1) : 0) : 0);
        $gen = ($bill != null ? ($bill->Item4 != null && is_numeric($bill->Item4) ? floatval($bill->Item4) : 0) : 0);

        return round($partialAmt - $gen, 2);
    }

    /**
     * COMPUTES CONTEATABLES AND DONT SAVE
     */
    public static function computeContestableAndDontSave($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307, $demand) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $demand = round(floatval($demand), 2);
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->PreviousKwh = $prev;
                    $bill->BillingDate = $readDate;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    // $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }

    /**
     * COMPUTES CONTESTABLES AND SAVE TO DB
     */
    public static function computeContestable($account, $billId, $kwh, $prev, $pres, $period, $readDate, $additionalCharges, $deductions, $is2307, $demand) {
        $rate = Rates::where('ConsumerType', Bills::getAccountType($account))
            ->where('ServicePeriod', $period)
            ->where('AreaCode', $account->Town)
            ->first();

        $meter = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $account->id)
            ->orderByDesc('created_at')
            ->first();

        /**
         * GET OCL
         */
        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $period)
            ->first();

        /**
         * PREPAYMENT
         */
        $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $account->id)->first();
        $latestPrepaymentHistory = PrePaymentTransHistory::where('AccountNumber', $account->id)
            ->whereRaw("Method='DEPOSIT' AND ORNumber IS NOT NULL")
            ->orderByDesc('created_at')
            ->first();

        if ($rate != null) {            
            // VARIABLES
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);
            $kwhAmountUsed = round(floatval($kwh), 2);
            $multiplier = round(floatval($account->Multiplier != null ? $account->Multiplier : 1), 2);
            $kwh = $kwhAmountUsed;
            $demand = round(floatval($demand), 2);
            $additionalCharges = $ocl != null ? round(floatval($ocl->Amount), 2) : 0;
            $deductions = round(floatval($deductions), 2);

            // IF BILL UPDATE
            if ($billId != null) {
                $bill = Bills::find($billId);

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;  

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  
                    
                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);

                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    }                    
                    
                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);
                    
                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = null;
                }
            } else { // IF NEW BILL
                // QUERY FIRST IF BILL EXISTS
                $bill = Bills::where('ServicePeriod', $period)
                    ->where('AccountNumber', $account->id)
                    ->first();

                if ($bill != null) {
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;    
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;   

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($bill->DeductedDeposit != null && floatval($bill->DeductedDeposit) > 0) {
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;
                        $prepDep = floatval($bill->DeductedDeposit);

                        if ($prepDep >= $netAmnt) {
                            // PAY AUTOMATICALLY
                            $excess = $prepDep - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // RETURN EXCESS TO PREPAYMENTS
                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEPOSIT';
                            $transHistory->Notes = 'Excess of Bill Adjustment';
                            $transHistory->Amount = $bill->ExcessDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round((floatval($prepaymentBalance->Balance) + $excess), 2) . '';
                            $prepaymentBalance->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepDep;

                            $bill->DeductedDeposit = round($prepDep, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);
                        }
                    } else {
                        if ($prepaymentBalance != null) {
                            $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                            $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                            // PAY AUTOMATICALLY
                            if ($prepBal >= $netAmnt) {
                                $excess = $prepBal - $netAmnt;

                                $bill->DeductedDeposit = round($netAmnt, 2);
                                $bill->ExcessDeposit = round($excess, 2);

                                $bill->NetAmount = 0;

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = round($excess, 2) . '';
                                $prepaymentBalance->save();
                            } else {
                                // DEDUCT
                                $excessNet = $netAmnt - $prepBal;

                                $bill->DeductedDeposit = round($prepBal, 2);
                                $bill->ExcessDeposit = 0;
                                $bill->NetAmount = round($excessNet, 2);

                                // SAVE PREPAYMENT HISTORY
                                $transHistory = new PrePaymentTransHistory;
                                $transHistory->id = IDGenerator::generateIDandRandString();
                                $transHistory->AccountNumber = $bill->AccountNumber;
                                $transHistory->Method = 'DEDUCT';
                                $transHistory->Amount = $bill->DeductedDeposit;
                                $transHistory->UserId = Auth::id(); 
                                $transHistory->save();

                                // UPDATE PREPAYMET
                                $prepaymentBalance->Balance = '0';
                                $prepaymentBalance->save();
                            }
                        } else {
                            $bill->DeductedDeposit = 0;
                            $bill->ExcessDeposit = 0;
                        }
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                } else {
                    $bill = new Bills;
                    $bill->id = IDGenerator::generateIDandRandString();
                    $bill->BillNumber = IDGenerator::generateBillNumber($account->Town);
                    $bill->AccountNumber = $account->id;
                    $bill->ServicePeriod = $period;
                    $bill->Multiplier = $account->Multiplier;
                    $bill->Coreloss = $account->Coreloss;
                    $bill->KwhUsed = $kwhAmountUsed;
                    $bill->DemandPresentKwh = $demand;
                    $bill->PreviousKwh = $prev;
                    $bill->PresentKwh = round(floatval($pres), 2);
                    $bill->EffectiveRate = $effectiveRate;
                    $bill->KwhAmount = round($kwh * $effectiveRate, 2);
                    $bill->AdditionalCharges = $additionalCharges;
                    $bill->BillingDate = $readDate;
                    $bill->Deductions = $deductions;
                    $bill->ServiceDateFrom = Bills::getServiceDateFrom($account->id, $readDate, $period);
                    $bill->ServiceDateTo = $readDate;
                    $bill->DueDate = Bills::createDueDate($readDate);
                    $bill->MeterNumber = $meter != null ? $meter->SerialNumber : null;
                    $bill->ConsumerType = $account->AccountType;
                    $bill->BillType = $account->AccountType;  
                    $bill->Multiplier = $account->Multiplier;  
                    $bill->Coreloss = $account->Coreloss;     

                    // CHARGES
                    $bill->GenerationSystemCharge = 0;
                    $bill->TransmissionDeliveryChargeKW = round($demand * Rates::floatRate($rate->TransmissionDeliveryChargeKW), 2);
                    $bill->TransmissionDeliveryChargeKWH = round($kwh * Rates::floatRate($rate->TransmissionDeliveryChargeKWH), 2);
                    $bill->SystemLossCharge = round($kwh * Rates::floatRate($rate->SystemLossCharge), 2);
                    $bill->DistributionDemandCharge = round($demand * Rates::floatRate($rate->DistributionDemandCharge), 2);
                    $bill->DistributionSystemCharge = round($kwh * Rates::floatRate($rate->DistributionSystemCharge), 2);
                    $bill->SupplyRetailCustomerCharge = round(Rates::floatRate($rate->SupplyRetailCustomerCharge), 2);
                    $bill->SupplySystemCharge = round($kwh * Rates::floatRate($rate->SupplySystemCharge), 2);
                    $bill->MeteringRetailCustomerCharge = round(Rates::floatRate($rate->MeteringRetailCustomerCharge), 2);
                    $bill->MeteringSystemCharge = round($kwh * Rates::floatRate($rate->MeteringSystemCharge), 2);
                    $bill->RFSC = round($kwh * Rates::floatRate($rate->RFSC), 2);
                    $bill->InterClassCrossSubsidyCharge = round($kwh * Rates::floatRate($rate->InterClassCrossSubsidyCharge), 2);
                    $bill->PPARefund = round($kwh * Rates::floatRate($rate->PPARefund), 2);
                    $bill->MissionaryElectrificationCharge = round($kwh * Rates::floatRate($rate->MissionaryElectrificationCharge), 2);
                    $bill->EnvironmentalCharge = round($kwh * Rates::floatRate($rate->EnvironmentalCharge), 2);
                    $bill->StrandedContractCosts = round($kwh * Rates::floatRate($rate->StrandedContractCosts), 2);
                    $bill->NPCStrandedDebt = round($kwh * Rates::floatRate($rate->NPCStrandedDebt), 2);
                    $bill->FeedInTariffAllowance = 0;
                    $bill->MissionaryElectrificationREDCI = round($kwh * Rates::floatRate($rate->MissionaryElectrificationREDCI), 2);
                    // $bill->GenerationVAT = round($kwh * Rates::floatRate($rate->GenerationVAT), 2);
                    $bill->GenerationVAT = 0;
                    $bill->TransmissionVAT = round($kwh * Rates::floatRate($rate->TransmissionVAT), 2);
                    $bill->SystemLossVAT = round($kwh * Rates::floatRate($rate->SystemLossVAT), 2);
                    
                    if ($account->Item1 == 'Yes') {
                        $bill->RealPropertyTax = 0;
                    } else {
                        $bill->RealPropertyTax = round($kwh * Rates::floatRate($rate->RealPropertyTax), 2);
                    }  

                    $bill->OtherGenerationRateAdjustment = round($kwh * Rates::floatRate($rate->OtherGenerationRateAdjustment), 2);
                    $bill->OtherTransmissionCostAdjustmentKW = round($demand * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKW), 2);
                    $bill->OtherTransmissionCostAdjustmentKWH = round($kwh * Rates::floatRate($rate->OtherTransmissionCostAdjustmentKWH), 2);
                    $bill->OtherSystemLossCostAdjustment = round($kwh * Rates::floatRate($rate->OtherSystemLossCostAdjustment), 2);
                    $bill->OtherLifelineRateCostAdjustment = round($kwh * Rates::floatRate($rate->OtherLifelineRateCostAdjustment), 2);
                    
                    if ($account->SeniorCitizen == 'Yes' && $kwh <= 100) {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = 0;
                    } else {
                        $bill->SeniorCitizenDiscountAndSubsidyAdjustment = round($kwh * Rates::floatRate($rate->SeniorCitizenDiscountAndSubsidyAdjustment), 2);
                    } 

                    $bill->FranchiseTax = round($kwh * Rates::floatRate($rate->FranchiseTax), 2);
                    $bill->BusinessTax = round($kwh * Rates::floatRate($rate->BusinessTax), 2);

                    $bill->LifelineRate = round(Bills::computeLifeLine($account, $bill, $rate), 2);
                    $bill->SeniorCitizenSubsidy = round(Bills::computeSeniorCitizen($account, $bill, $rate), 2);

                    $bill->DistributionVAT = round(Bills::getDistributionVat($bill), 2);
                    // $bill->DistributionVAT = round($kwh * Rates::floatRate($rate->DistributionVAT), 2);

                    /**
                     * COMPUTE EVAT
                     */
                    if ($account->Evat5Percent=='Yes') {
                        $bill->Evat5Percent = round(Bills::getFivePercent($bill), 2);
                    } else {
                        $bill->Evat5Percent = '0';
                    }

                    if ($account->Ewt2Percent=='Yes') {
                        $bill->Evat2Percent = round(Bills::getTwoPercent($bill), 2);
                    } else {
                        $bill->Evat2Percent = '0';
                    }

                    if ($is2307 == 'true') {
                        $form2307 = Bills::get2307($bill);
                        $bill->Form2307Amount = $form2307;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill) - $form2307;
                    } else {
                        $form2307 = -floatval($bill->Form2307Amount);
                        $bill->Form2307Amount = null;

                        // TO BE CREATED DYNAMICALLY
                        $bill->NetAmount = Bills::computeNetAmount($bill);
                    }

                    $bill->NetAmount = round($bill->NetAmount, 2);

                    /**
                     * PREPAYMENT
                     */
                    if ($prepaymentBalance != null) {
                        $prepBal = $prepaymentBalance->Balance != null && is_numeric($prepaymentBalance->Balance) ? floatval($prepaymentBalance->Balance) : 0;
                        $netAmnt = $bill->NetAmount != null ? floatval($bill->NetAmount) : 0;

                        // PAY AUTOMATICALLY
                        if ($prepBal >= $netAmnt) {
                            $excess = $prepBal - $netAmnt;

                            $bill->DeductedDeposit = round($netAmnt, 2);
                            $bill->ExcessDeposit = round($excess, 2);

                            $bill->NetAmount = 0;

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = round($excess, 2) . '';
                            $prepaymentBalance->save();

                            // INSERT TO PAID BILLS
                            $paidBills = new PaidBills;
                            $paidBills->id = IDGenerator::generateIDandRandString();
                            $paidBills->BillNumber = $bill->BillNumber;
                            $paidBills->AccountNumber = $bill->AccountNumber;
                            $paidBills->ServicePeriod = $bill->ServicePeriod;
                            $paidBills->ORNumber = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->ORNumber : IDGenerator::generateID();
                            $paidBills->ORDate = $latestPrepaymentHistory !=null ? date('Y-m-d', strtotime($latestPrepaymentHistory->created_at)) : date('Y-m-d');
                            $paidBills->KwhUsed = $bill->KwhUsed;
                            $paidBills->Teller = $latestPrepaymentHistory !=null ? $latestPrepaymentHistory->UserId : Auth::id();
                            $paidBills->OfficeTransacted = env('APP_LOCATION');
                            $paidBills->PostingDate = date('Y-m-d');
                            $paidBills->PostingTime = date('H:i:s');
                            $paidBills->Surcharge = 0;
                            $paidBills->Deductions = $bill->DeductedDeposit;
                            $paidBills->NetAmount = "0";
                            $paidBills->Source = 'MONTHLY BILL - Pre-Payments';
                            $paidBills->ObjectSourceId = $bill->id;
                            $paidBills->UserId = Auth::id();
                            $paidBills->save();
                        } else {
                            // DEDUCT
                            $excessNet = $netAmnt - $prepBal;

                            $bill->DeductedDeposit = round($prepBal, 2);
                            $bill->ExcessDeposit = 0;
                            $bill->NetAmount = round($excessNet, 2);

                            // SAVE PREPAYMENT HISTORY
                            $transHistory = new PrePaymentTransHistory;
                            $transHistory->id = IDGenerator::generateIDandRandString();
                            $transHistory->AccountNumber = $bill->AccountNumber;
                            $transHistory->Method = 'DEDUCT';
                            $transHistory->Amount = $bill->DeductedDeposit;
                            $transHistory->UserId = Auth::id(); 
                            $transHistory->save();

                            // UPDATE PREPAYMET
                            $prepaymentBalance->Balance = '0';
                            $prepaymentBalance->save();
                        }
                    } else {
                        $bill->DeductedDeposit = 0;
                        $bill->ExcessDeposit = 0;
                    }
                    
                    $bill->BilledFrom = 'WEB';
                    $bill->UserId = Auth::id();

                    $bill->save();
                }
                
            }
            return $bill;
        } else {
            return null;
        }
    }
}
