<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DCRSummaryTransactions
 * @package App\Models
 * @version April 25, 2022, 9:33 am PST
 *
 * @property string $GLCode
 * @property string $NEACode
 * @property string $Description
 * @property string $Amount
 * @property string $Day
 * @property time $Time
 * @property string $Teller
 * @property string $DCRNumber
 * @property string $Status
 */
class DCRSummaryTransactions extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_DCRSummaryTransactions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'GLCode',
        'NEACode',
        'Description',
        'Amount',
        'Day',
        'Time',
        'Teller',
        'DCRNumber',
        'Status',
        'ORNumber',
        'ReportDestination',
        'Office',
        'AccountNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'GLCode' => 'string',
        'NEACode' => 'string',
        'Description' => 'string',
        'Amount' => 'string',
        'Day' => 'date',
        'Teller' => 'string',
        'DCRNumber' => 'string',
        'Status' => 'string',
        'ORNumber' => 'string',
        'ReportDestination' => 'string',
        'Office' => 'string',
        'AccountNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'GLCode' => 'nullable|string|max:255',
        'NEACode' => 'nullable|string|max:255',
        'Description' => 'nullable|string|max:255',
        'Amount' => 'nullable|string|max:255',
        'Day' => 'nullable',
        'Time' => 'nullable',
        'Teller' => 'nullable|string|max:255',
        'DCRNumber' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ORNumber' => 'nullable|string',
        'ReportDestination' => 'nullable|string',
        'Office' => 'nullable|string',
        'AccountNumber' => 'nullable|string'
    ];

    public static function getARConsumersAmount($bill) {
        $amount = 0.0;

        $amount = floatval($bill->GenerationSystemCharge) +
                floatval($bill->TransmissionDeliveryChargeKW) +
                floatval($bill->TransmissionDeliveryChargeKWH) + 
                floatval($bill->SystemLossCharge) +
                floatval($bill->DistributionDemandCharge) + 
                floatval($bill->DistributionSystemCharge) + 
                floatval($bill->SupplyRetailCustomerCharge) + 
                floatval($bill->SupplySystemCharge) +
                floatval($bill->MeteringRetailCustomerCharge) + 
                floatval($bill->MeteringSystemCharge) + 
                floatval($bill->OtherGenerationRateAdjustment) +
                floatval($bill->OtherTransmissionCostAdjustmentKW) +
                floatval($bill->OtherTransmissionCostAdjustmentKWH) +
                floatval($bill->OtherSystemLossCostAdjustment) +
                floatval($bill->OtherLifelineRateCostAdjustment) +
                floatval($bill->LifelineRate) +
                floatval($bill->SeniorCitizenSubsidy) +
                floatval($bill->SeniorCitizenDiscountAndSubsidyAdjustment);

        return round($amount, 4);
    }

    public static function getARConsumersAmountAdjustment($bill, $type) { // Bills, Original
        $amount = 0.0;

        if ($type == 'Bills') {
            $amount = floatval($bill->BillsGenerationSystemCharge) +
                floatval($bill->BillsTransmissionDeliveryChargeKW) +
                floatval($bill->BillsTransmissionDeliveryChargeKWH) + 
                floatval($bill->BillsSystemLossCharge) +
                floatval($bill->BillsDistributionDemandCharge) + 
                floatval($bill->BillsDistributionSystemCharge) + 
                floatval($bill->BillsSupplyRetailCustomerCharge) + 
                floatval($bill->BillsSupplySystemCharge) +
                floatval($bill->BillsMeteringRetailCustomerCharge) + 
                floatval($bill->BillsMeteringSystemCharge) + 
                floatval($bill->BillsOtherGenerationRateAdjustment) +
                floatval($bill->BillsOtherTransmissionCostAdjustmentKW) +
                floatval($bill->BillsOtherTransmissionCostAdjustmentKWH) +
                floatval($bill->BillsOtherSystemLossCostAdjustment) +
                floatval($bill->BillsOtherLifelineRateCostAdjustment) +
                floatval($bill->BillsLifelineRate) +
                floatval($bill->BillsSeniorCitizenSubsidy) +
                floatval($bill->BillsSeniorCitizenDiscountAndSubsidyAdjustment);
        } else {
            $amount = floatval($bill->OriginalGenerationSystemCharge) +
                floatval($bill->OriginalTransmissionDeliveryChargeKW) +
                floatval($bill->OriginalTransmissionDeliveryChargeKWH) + 
                floatval($bill->OriginalSystemLossCharge) +
                floatval($bill->OriginalDistributionDemandCharge) + 
                floatval($bill->OriginalDistributionSystemCharge) + 
                floatval($bill->OriginalSupplyRetailCustomerCharge) + 
                floatval($bill->OriginalSupplySystemCharge) +
                floatval($bill->OriginalMeteringRetailCustomerCharge) + 
                floatval($bill->OriginalMeteringSystemCharge) + 
                floatval($bill->OriginalOtherGenerationRateAdjustment) +
                floatval($bill->OriginalOtherTransmissionCostAdjustmentKW) +
                floatval($bill->OriginalOtherTransmissionCostAdjustmentKWH) +
                floatval($bill->OriginalOtherSystemLossCostAdjustment) +
                floatval($bill->OriginalOtherLifelineRateCostAdjustment) +
                floatval($bill->OriginalLifelineRate) +
                floatval($bill->OriginalSeniorCitizenSubsidy) +
                floatval($bill->OriginalSeniorCitizenDiscountAndSubsidyAdjustment);
        }
        

        return round($amount, 4);
    }

    public static function getARConsumers($town) {
        if ($town == "01") {
            return '140-142-50'; // Cadiz
        } elseif($town == "02") {
            return '140-142-20'; // EB Magalona
        } elseif($town == "03") {
            return '140-142-40'; // Manapla
        } elseif($town == "04") {
            return '140-142-30'; // Victorias
        } elseif($town == "05") {
            return '140-142-80'; // San Carlos
        } elseif($town == "06") {
            return '140-142-60'; // Sagay
        } elseif($town == "07") {
            return '140-142-70'; // Escalante
        } elseif($town == "08") {
            return '140-142-81'; // Calatrava
        } elseif($town == "09") {
            return '140-142-71'; // Toboso
        } else {
            return '0'; // Null
        }
    }

    public static function getARConsumersPerArea($area) {
        if ($area == "CADIZ") {
            return '140-142-50'; // Cadiz
        } elseif($area == "EB MAGALONA") {
            return '140-142-20'; // EB Magalona
        } elseif($area == "MANAPLA") {
            return '140-142-40'; // Manapla
        } elseif($area == "VICTORIAS") {
            return '140-142-30'; // Victorias
        } elseif($area == "SAN CARLOS") {
            return '140-142-80'; // San Carlos
        } elseif($area == "SAGAY") {
            return '140-142-60'; // Sagay
        } elseif($area == "ESCALANTE") {
            return '140-142-70'; // Escalante
        } elseif($area == "CALATRAVA") {
            return '140-142-81'; // Calatrava
        } elseif($area == "TOBOSO") {
            return '140-142-71'; // Toboso
        } else {
            return '0'; // Null
        }
    }

    public static function getARConsumersRPT($town) {
        if ($town == "01") {
            return '140-143-05'; // Cadiz
        } elseif($town == "02") {
            return '140-143-02'; // EB Magalona
        } elseif($town == "03") {
            return '140-143-04'; // Manapla
        } elseif($town == "04") {
            return '140-143-03'; // Victorias
        } elseif($town == "05") {
            return '140-143-08'; // San Carlos
        } elseif($town == "06") {
            return '140-143-06'; // Sagay
        } elseif($town == "07") {
            return '140-143-07'; // Escalante
        } elseif($town == "08") {
            return '140-143-18'; // Calatrava
        } elseif($town == "09") {
            return '140-143-17'; // Toboso
        } else {
            return '0'; // Null
        }
    }

    public static function getARConsumersTermedPayments($town) {
        if ($town == "01") {
            return '140-142-67'; // Cadiz
        } elseif($town == "02") {
            return '140-142-64'; // EB Magalona
        } elseif($town == "03") {
            return '140-142-66'; // Manapla
        } elseif($town == "04") {
            return '140-142-65'; // Victorias
        } elseif($town == "05") {
            return '140-142-75'; // San Carlos
        } elseif($town == "06") {
            return '140-142-77'; // Sagay
        } elseif($town == "07") {
            return '140-142-68'; // Escalante
        } elseif($town == "08") {
            return '140-142-76'; // Calatrava
        } elseif($town == "09") {
            return '140-142-69'; // Toboso
        } else {
            return '0'; // Null
        }
    }

    public static function getGLCodePerAccountType($type) {
        if ($type == 'COMMERCIAL' || $type == 'COMMERCIAL HIGH VOLTAGE') {
            return '311-442-00';
        } elseif ($type == 'PUBLIC BUILDING' || $type == 'PUBLIC BUILDING HIGH VOLTAGE') {
            return '311-445-00';
        } elseif ($type == 'INDUSTRIAL' || $type == 'INDUSTRIAL HIGH VOLTAGE') {
            return '311-443-00';
        } elseif ($type == 'STREET LIGHTS') {
            return '311-444-00';
        } elseif ($type == 'IRRIGATION/WATER SYSTEMS') {
            return '311-446-00';
        } elseif ($type == 'BAPA') {
            return '311-448-00';
        }
    }

    public static function getSalesGenTransSysLossVatAmount($bill) {
        $amount = 0.0;

        $amount = floatval($bill->GenerationVAT) +
                floatval($bill->TransmissionVAT) +
                floatval($bill->SystemLossVAT);

        return round($amount, 4);
    }

    public static function getSalesDistOthersVatAmount($bill) {
        $amount = 0.0;

        $amount = floatval($bill->DistributionVAT);

        return round($amount, 4);
    }
}
