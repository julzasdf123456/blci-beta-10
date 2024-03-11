<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionInspections
 * @package App\Models
 * @version July 26, 2021, 7:43 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $SEMainCircuitBreakerAsPlan
 * @property string $SEMainCircuitBreakerAsInstalled
 * @property string $SENoOfBranchesAsPlan
 * @property string $SENoOfBranchesAsInstalled
 * @property string $PoleGIEstimatedDiameter
 * @property string $PoleGIHeight
 * @property string $PoleGINoOfLiftPoles
 * @property string $PoleConcreteEstimatedDiameter
 * @property string $PoleConcreteHeight
 * @property string $PoleConcreteNoOfLiftPoles
 * @property string $PoleHardwoodEstimatedDiameter
 * @property string $PoleHardwoodHeight
 * @property string $PoleHardwoodNoOfLiftPoles
 * @property string $PoleRemarks
 * @property string $SDWSizeAsPlan
 * @property string $SDWSizeAsInstalled
 * @property string $SDWLengthAsPlan
 * @property string $SDWLengthAsInstalled
 * @property string $GeoBuilding
 * @property string $GeoTappingPole
 * @property string $GeoMeteringPole
 * @property string $GeoSEPole
 * @property string $FirstNeighborName
 * @property string $FirstNeighborMeterSerial
 * @property string $SecondNeighborName
 * @property string $SecondNeighborMeterSerial
 * @property string $EngineerInchargeName
 * @property string $EngineerInchargeTitle
 * @property string $EngineerInchargeLicenseNo
 * @property string $EngineerInchargeLicenseValidity
 * @property string $EngineerInchargeContactNo
 * @property string $Status
 * @property string $Inspector
 * @property string|\Carbon\Carbon $DateOfVerification
 * @property string $EstimatedDateForReinspection
 * @property string $Notes
 */
class ServiceConnectionInspections extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionInspections';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;



    public $fillable = [
        'id',
        'ServiceConnectionId',
        'SEMainCircuitBreakerAsPlan',
        'SEMainCircuitBreakerAsInstalled',
        'SENoOfBranchesAsPlan',
        'SENoOfBranchesAsInstalled',
        'PoleNumber',
        'PoleGIEstimatedDiameter',
        'PoleGIHeight',
        'PoleGINoOfLiftPoles',
        'PoleConcreteEstimatedDiameter',
        'PoleConcreteHeight',
        'PoleConcreteNoOfLiftPoles',
        'PoleHardwoodEstimatedDiameter',
        'PoleHardwoodHeight',
        'PoleHardwoodNoOfLiftPoles',
        'PoleRemarks',
        'SDWSizeAsPlan',
        'SDWSizeAsInstalled',
        'SDWLengthAsPlan',
        'SDWLengthAsInstalled',
        'GeoBuilding',
        'GeoTappingPole',
        'GeoMeteringPole',
        'GeoSEPole',
        'FirstNeighborName',
        'FirstNeighborMeterSerial',
        'SecondNeighborName',
        'SecondNeighborMeterSerial',
        'EngineerInchargeName',
        'EngineerInchargeTitle',
        'EngineerInchargeLicenseNo',
        'EngineerInchargeLicenseValidity',
        'EngineerInchargeContactNo',
        'Status',
        'Inspector',
        'DateOfVerification',
        'EstimatedDateForReinspection',
        'Notes',

        'InspectionSchedule',
        'ReInspectionSchedule',
        'LightingOutlets',
        'ConvenienceOutlets',
        'Motor',
        'TotalLoad',
        'ContractedDemand',
        'ContractedEnergy',
        'DistanceFromSecondaryLine',
        'SizeOfSecondary',
        'SizeOfSDW',
        'TypeOfSDW',
        'ServiceEntranceStatus',
        'HeightOfSDW',
        'DistanceFromTransformer',
        'SizeOfTransformer',
        'TransformerNo',
        'PoleNo',
        'ConnectedFeeder',
        'SizeOfSvcPoles',
        'HeightOfSvcPoles',
        'LinePassingPrivateProperty',
        'WrittenConsentByPropertyOwner',
        'ObstructionOfLines',
        'LinePassingRoads',
        'Recommendation',
        'ForPayment',
        'Rate',
        'BillDeposit',
        'MeteringType',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'SEMainCircuitBreakerAsPlan' => 'string',
        'SEMainCircuitBreakerAsInstalled' => 'string',
        'SENoOfBranchesAsPlan' => 'string',
        'SENoOfBranchesAsInstalled' => 'string',
        'PoleNumber' => 'string',
        'PoleGIEstimatedDiameter' => 'string',
        'PoleGIHeight' => 'string',
        'PoleGINoOfLiftPoles' => 'string',
        'PoleConcreteEstimatedDiameter' => 'string',
        'PoleConcreteHeight' => 'string',
        'PoleConcreteNoOfLiftPoles' => 'string',
        'PoleHardwoodEstimatedDiameter' => 'string',
        'PoleHardwoodHeight' => 'string',
        'PoleHardwoodNoOfLiftPoles' => 'string',
        'PoleRemarks' => 'string',
        'SDWSizeAsPlan' => 'string',
        'SDWSizeAsInstalled' => 'string',
        'SDWLengthAsPlan' => 'string',
        'SDWLengthAsInstalled' => 'string',
        'GeoBuilding' => 'string',
        'GeoTappingPole' => 'string',
        'GeoMeteringPole' => 'string',
        'GeoSEPole' => 'string',
        'FirstNeighborName' => 'string',
        'FirstNeighborMeterSerial' => 'string',
        'SecondNeighborName' => 'string',
        'SecondNeighborMeterSerial' => 'string',
        'EngineerInchargeName' => 'string',
        'EngineerInchargeTitle' => 'string',
        'EngineerInchargeLicenseNo' => 'string',
        'EngineerInchargeLicenseValidity' => 'date',
        'EngineerInchargeContactNo' => 'string',
        'Status' => 'string',
        'Inspector' => 'string',
        'DateOfVerification' => 'datetime',
        'EstimatedDateForReinspection' => 'date',
        'Notes' => 'string',
        'InspectionSchedule' => 'string',
        'ReInspectionSchedule' => 'string',

        'LightingOutlets' => 'string',
        'ConvenienceOutlets' => 'string',
        'Motor' => 'string',
        'TotalLoad' => 'string',
        'ContractedDemand' => 'string',
        'ContractedEnergy' => 'string',
        'DistanceFromSecondaryLine' => 'string',
        'SizeOfSecondary' => 'string',
        'SizeOfSDW' => 'string',
        'TypeOfSDW' => 'string',
        'ServiceEntranceStatus' => 'string',
        'HeightOfSDW' => 'string',
        'DistanceFromTransformer' => 'string',
        'SizeOfTransformer' => 'string',
        'TransformerNo' => 'string',
        'PoleNo' => 'string',
        'ConnectedFeeder' => 'string',
        'SizeOfSvcPoles' => 'string',
        'HeightOfSvcPoles' => 'string',
        'LinePassingPrivateProperty' => 'string',
        'WrittenConsentByPropertyOwner' => 'string',
        'ObstructionOfLines' => 'string',
        'LinePassingRoads' => 'string',
        'Recommendation' => 'string',
        'ForPayment' => 'string',
        'Rate' => 'string',
        'BillDeposit' => 'string',
        'MeteringType' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'SEMainCircuitBreakerAsPlan' => 'nullable|string|max:255',
        'SEMainCircuitBreakerAsInstalled' => 'nullable|string|max:255',
        'SENoOfBranchesAsPlan' => 'nullable|string|max:255',
        'SENoOfBranchesAsInstalled' => 'nullable|string|max:255',
        'PoleGIEstimatedDiameter' => 'nullable|string|max:255',
        'PoleNumber' => 'nullable|string',
        'PoleGIHeight' => 'nullable|string|max:255',
        'PoleGINoOfLiftPoles' => 'nullable|string|max:255',
        'PoleConcreteEstimatedDiameter' => 'nullable|string|max:255',
        'PoleConcreteHeight' => 'nullable|string|max:255',
        'PoleConcreteNoOfLiftPoles' => 'nullable|string|max:255',
        'PoleHardwoodEstimatedDiameter' => 'nullable|string|max:255',
        'PoleHardwoodHeight' => 'nullable|string|max:255',
        'PoleHardwoodNoOfLiftPoles' => 'nullable|string|max:255',
        'PoleRemarks' => 'nullable|string|max:2000',
        'SDWSizeAsPlan' => 'nullable|string|max:255',
        'SDWSizeAsInstalled' => 'nullable|string|max:255',
        'SDWLengthAsPlan' => 'nullable|string|max:255',
        'SDWLengthAsInstalled' => 'nullable|string|max:255',
        'GeoBuilding' => 'nullable|string|max:500',
        'GeoTappingPole' => 'nullable|string|max:500',
        'GeoMeteringPole' => 'nullable|string|max:500',
        'GeoSEPole' => 'nullable|string|max:500',
        'FirstNeighborName' => 'nullable|string|max:1000',
        'FirstNeighborMeterSerial' => 'nullable|string|max:1000',
        'SecondNeighborName' => 'nullable|string|max:1000',
        'SecondNeighborMeterSerial' => 'nullable|string|max:1000',
        'EngineerInchargeName' => 'nullable|string|max:600',
        'EngineerInchargeTitle' => 'nullable|string|max:255',
        'EngineerInchargeLicenseNo' => 'nullable|string|max:600',
        'EngineerInchargeLicenseValidity' => 'nullable',
        'EngineerInchargeContactNo' => 'nullable|string|max:600',
        'Status' => 'nullable|string|max:255',
        'Inspector' => 'nullable|string|max:255',
        'DateOfVerification' => 'nullable',
        'EstimatedDateForReinspection' => 'nullable',
        'Notes' => 'nullable|string|max:2000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'InspectionSchedule' => 'nullable|string',
        'ReInspectionSchedule' => 'nullable|string',

        'LightingOutlets' => 'nullable|string',
        'ConvenienceOutlets' => 'nullable|string',
        'Motor' => 'nullable|string',
        'TotalLoad' => 'nullable|string',
        'ContractedDemand' => 'nullable|string',
        'ContractedEnergy' => 'nullable|string',
        'DistanceFromSecondaryLine' => 'nullable|string',
        'SizeOfSecondary' => 'nullable|string',
        'SizeOfSDW' => 'nullable|string',
        'TypeOfSDW' => 'nullable|string',
        'ServiceEntranceStatus' => 'nullable|string',
        'HeightOfSDW' => 'nullable|string',
        'DistanceFromTransformer' => 'nullable|string',
        'SizeOfTransformer' => 'nullable|string',
        'TransformerNo' => 'nullable|string',
        'PoleNo' => 'nullable|string',
        'ConnectedFeeder' => 'nullable|string',
        'SizeOfSvcPoles' => 'nullable|string',
        'HeightOfSvcPoles' => 'nullable|string',
        'LinePassingPrivateProperty' => 'nullable|string',
        'WrittenConsentByPropertyOwner' => 'nullable|string',
        'ObstructionOfLines' => 'nullable|string',
        'LinePassingRoads' => 'nullable|string',
        'Recommendation' => 'nullable|string',
        'ForPayment' => 'nullable|string',
        'Rate' => 'nullable|string',
        'BillDeposit' => 'nullable|string',
        'MeteringType' => 'nullable|string',
    ];

    public static function df() {
        return .8;
    }

    public static function pf() {
        return .8;
    }

    public static function pfCommercial() {
        return .5;
    }

    public static function pfResidential() {
        return .35;
    }

    public static function commercialThreshold() {
        return 10000;
    }

    public static function resdidentialThreshold() {
        return 3000;
    }
}
