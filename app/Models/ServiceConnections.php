<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnections
 * @package App\Models
 * @version July 21, 2021, 6:12 am UTC
 *
 * @property string $MemberConsumerId
 * @property string $DateOfApplication
 * @property string $ServiceAccountName
 * @property integer $AccountCount
 * @property string $Sitio
 * @property string $Barangay
 * @property string $Town
 * @property string $ContactNumber
 * @property string $EmailAddress
 * @property string $AccountType
 * @property string $AccountOrganization
 * @property string $OrganizationAccountNumber
 * @property string $IsNIHE
 * @property string $AccountApplicationType
 * @property string $ConnectionApplicationType
 * @property string $Status
 * @property string $Notes
 */
class ServiceConnections extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnections';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    
    protected $primaryKey = 'id';

    public $incrementing = false;


    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'MemberConsumerId',
        'DateOfApplication',
        'ServiceAccountName',
        'AccountCount',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumber',
        'EmailAddress',
        'AccountType',
        'AccountOrganization',
        'OrganizationAccountNumber',
        'IsNIHE',
        'AccountApplicationType',
        'ConnectionApplicationType',
        'BuildingType',
        'Status',
        'Notes',
        'Trash',
        'ORNumber',
        'ORDate',
        'DateTimeLinemenArrived',
        'DateTimeOfEnergization',
        'EnergizationOrderIssued',
        'DateTimeOfEnergizationIssue',
        'StationCrewAssigned',
        'LoadCategory',
        'TemporaryDurationInMonths',
        'LongSpan',
        'Office',
        'TypeOfOccupancy',
        'ResidenceNumber',
        'AccountNumber',
        'ServiceNumber',
        'UserId',
        'ConnectionSchedule',
        'TimeOfApplication',
        'CertificateOfConnectionIssuedOn',
        'LoadType',
        'LoadInKva',
        'Zone',
        'TransformerID',
        'PoleNumber',
        'Feeder',
        'ChargeTo',
        'Block',
        'TIN',
        'BarangayCode',
        'TypeOfCustomer',
        'NumberOfAccounts',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'MemberConsumerId' => 'string',
        'DateOfApplication' => 'date',
        'ServiceAccountName' => 'string',
        'AccountCount' => 'integer',
        'Sitio' => 'string',
        'Barangay' => 'string',
        'Town' => 'string',
        'ContactNumber' => 'string',
        'EmailAddress' => 'string',
        'AccountType' => 'string',
        'AccountOrganization' => 'string',
        'OrganizationAccountNumber' => 'string',
        'IsNIHE' => 'string',
        'AccountApplicationType' => 'string',
        'ConnectionApplicationType' => 'string',
        'BuildingType' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'Trash' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'DateTimeLinemenArrived' => 'datetime',
        'DateTimeOfEnergization' => 'datetime',
        'EnergizationOrderIssued' => 'string',
        'DateTimeOfEnergizationIssue' => 'datetime',
        'StationCrewAssigned' => 'string',
        'LoadCategory' => 'string',
        'TemporaryDurationInMonths' => 'string',
        'LongSpan' => 'string',
        'Office' => 'string',
        'TypeOfOccupancy' => 'string',
        'ResidenceNumber' => 'string',
        'AccountNumber' => 'string',
        'ServiceNumber' => 'string',
        'UserId' => 'string',
        'ConnectionSchedule' => 'string',
        'TimeOfApplication' => 'string',
        'CertificateOfConnectionIssuedOn' => 'string',
        'LoadType' => 'string',
        'LoadInKva' => 'string',
        'Zone' => 'string',
        'TransformerID' => 'string',
        'PoleNumber' => 'string',
        'Feeder' => 'string',
        'ChargeTo' => 'string',
        'Block' => 'string',
        'TIN' => 'string',
        'BarangayCode' => 'string',
        'TypeOfCustomer' => 'string',
        'NumberOfAccounts' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'MemberConsumerId' => 'nullable|string|max:255',
        'DateOfApplication' => 'nullable',
        'ServiceAccountName' => 'required|string|max:255',
        'AccountCount' => 'nullable|integer',
        'Sitio' => 'nullable|string|max:1000',
        'Barangay' => 'nullable|string|max:10',
        'Town' => 'nullable|string|max:10',
        'ContactNumber' => 'required|string|max:500',
        'EmailAddress' => 'nullable|string|max:800',
        'AccountType' => 'nullable|string|max:100',
        'AccountOrganization' => 'nullable|string|max:100',
        'OrganizationAccountNumber' => 'nullable|string|max:100',
        'IsNIHE' => 'nullable|string|max:255',
        'AccountApplicationType' => 'nullable|string|max:100',
        'ConnectionApplicationType' => 'nullable|string|max:100',
        'BuildingType' => 'nullable|string',
        'Status' => 'nullable|string|max:100',
        'Notes' => 'nullable|string|max:2000',
        'Trash' => 'nullable|string',
        'ORNumber' => 'nullable|string',
        'ORDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DateTimeLinemenArrived' => 'nullable',
        'DateTimeOfEnergization' => 'nullable',
        'EnergizationOrderIssued' => 'nullable|string',
        'DateTimeOfEnergizationIssue' => 'nullable',
        'StationCrewAssigned' => 'nullable|string',
        'LoadCategory' => 'nullable|string',
        'TemporaryDurationInMonths' => 'nullable|string',
        'LongSpan' => 'nullable|string',
        'Office' => 'nullable|string',
        'TypeOfOccupancy' => 'nullable|string',
        'ResidenceNumber' => 'nullable|string',
        'AccountNumber' => 'nullable|string',
        'ServiceNumber' => 'nullable|string',
        'UserId' => 'nullable|string',
        'ConnectionSchedule' => 'nullable|string',
        'TimeOfApplication' => 'nullable|string',
        'CertificateOfConnectionIssuedOn' => 'nullable|string',
        'LoadType' => 'nullable|string',
        'LoadInKva' => 'nullable|string',
        'Zone' => 'nullable|string',
        'TransformerID' => 'nullable|string',
        'PoleNumber' => 'nullable|string',
        'Feeder' => 'nullable|string',
        'ChargeTo' => 'nullable|string',
        'Block' => 'nullable|string',
        'TIN' => 'nullable|string',
        'BarangayCode' => 'nullable|string',
        'TypeOfCustomer' => 'nullable|string',
        'NumberOfAccounts' => 'nullable|string',
    ];

    public static function getAccountCount($consumerId) {
        $sc = ServiceConnections::where('MemberConsumerId', $consumerId)->get();

        if ($sc == null) {
            return 0;
        } else {
            return count($sc);
        }
    }

    public static function getContactInfo($serviceConnections) {
        if ($serviceConnections->ContactNumber==null && $serviceConnections->EmailAddress==null) {
            return 'not specified';
        } elseif ($serviceConnections->ContactNumber==null && $serviceConnections->EmailAddress!=null) {
            return $serviceConnections->EmailAddress;
        } elseif ($serviceConnections->ContactNumber!=null && $serviceConnections->EmailAddress==null) {
            return $serviceConnections->ContactNumber;
        } else {
            return $serviceConnections->ContactNumber . ' | ' . $serviceConnections->EmailAddress;
        }
    }

    public static function getAddress($serviceConnections) {
        if ($serviceConnections->Sitio==null && ($serviceConnections->Barangay!=null && $serviceConnections->Town!=null)) {
            return $serviceConnections->Barangay . ', ' . $serviceConnections->Town;
        } elseif($serviceConnections->Sitio!=null && ($serviceConnections->Barangay!=null && $serviceConnections->Town!=null)) {
            return $serviceConnections->Sitio . ', ' . $serviceConnections->Barangay . ', ' . $serviceConnections->Town;
        }
    }

    public static function getBillDepositId() {
        return '1629263796222';
    }

    public static function getBgStatus($status) {
        if ($status=='Energized' | $status=='Closed') {
            return 'bg-success';
        } elseif ($status=='For Inspection') {
            return 'bg-warning';
        } elseif ($status=='Approved' | $status=='Downloaded by Crew') {
            return 'bg-info';
        } elseif($status=='For Transformer and Pole Assigning' | $status=='Forwarded To Planning') {
            return 'bg-primary';
        } else {
            return 'bg-danger';
        }
    }

    public static function getProgressStatus($status) {
        if ($status=='For Inspection' || $status=='Re-Inspection') {
            return 14.28;
        } elseif ($status=='Approved') {
            return 28.56;
        } elseif($status=='Forwarded To Planning') {
            return 42.84;
        } elseif($status=='For Transformer and Pole Assigning') {
            return 57.12;
        } elseif ($status=='Downloaded by Crew') {
            return 71.4;
        } elseif($status=='Energized') {
            return 85.68;
        } elseif($status=='Closed') {
            return 100;
        }
    }

    public static function getOfficeBg($office) {
        if ($office == 'MAIN OFFICE') {
            return 'bg-primary';
        } elseif ($office == 'SUB-OFFICE') {
            return 'bg-danger';
        } else {
            return 'bg-info';
        }
    }

    public static function whHeadStatus() {
        return ['Administrator', 'Heads and Managers'];
    }

    public static function filePath() {
        return public_path() . "/scfiles/";
    }

    public static function typesOfConsumer() {
        return [
            '01' => 'Residential',
            '02' => 'Commercial-LV',
            '03' => 'Commercial-HV',
            '04' => 'Public Building-LV',
            '05' => 'Public Building-HV',
            '06' => 'Hospitals and Radio Stations-LV',
            '06' => 'Hospitals and Radio Stations-HV',
        ];
    }

    public static function skippableForInspection() {
        return [
            "CONDUCTOR COVER RENTAL",
            "METER ACCURACY TEST",
            "PURCHASE OF MATERIALS",
            "REFUND OF BILL DEPOSIT", // DIRECTLY DONE
            "SALE OF ITEMS",
            "SENIOR CITIZEN'S DISCOUNT", // DIRECTLY DONE
        ];
    }

    public static function defaultNewConnectionInspector() {
        return '1709265310093'; // joko
    }

    public static function defaultOtherApplicationsInspector() {
        return '1709067435299'; // ramil satago
    }
}
