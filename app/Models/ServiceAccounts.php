<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceAccounts
 * @package App\Models
 * @version September 13, 2021, 11:26 am PST
 *
 * @property string $ServiceAccountName
 * @property string $Town
 * @property string $Barangay
 * @property string $Purok
 * @property string $AccountType
 * @property string $AccountStatus
 * @property string $ContactNumber
 * @property string $EmailAddress
 * @property string $ServiceConnectionId
 * @property string $MeterDetailsId
 * @property string $TransformerDetailsId
 * @property string $PoleNumber
 * @property string $AreaCode
 * @property string $BlockCode
 * @property string $SequenceCode
 * @property string $Feeder
 * @property string $ComputeType
 * @property string $Organization
 * @property string $OrganizationParentAccount
 * @property string $GPSMeter
 */
class ServiceAccounts extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_ServiceAccounts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceAccountName',
        'Town',
        'Barangay',
        'Purok',
        'AccountType',
        'AccountStatus',
        'ContactNumber',
        'EmailAddress',
        'ServiceConnectionId',
        'MeterDetailsId',
        'TransformerDetailsId',
        'PoleNumber',
        'AreaCode',
        'BlockCode',
        'SequenceCode',
        'Feeder',
        'ComputeType',
        'Organization',
        'OrganizationParentAccount',
        'GPSMeter',
        'OldAccountNo',
        'UserId',
        'MeterReader',
        'GroupCode',
        'ForDistribution',
        'Multiplier',
        'Coreloss',
        'Main',
        'Evat5Percent',
        'Ewt2Percent',
        'AccountCount',
        'ConnectionDate',
        'LatestReadingDate',
        'DateDisconnected',
        'DateTransfered',
        'SeniorCitizen',
        'AccountPaymentType',
        'Latitude',
        'Longitude',
        'AccountRetention',
        'AccountExpiration',
        'DurationInMonths',
        'Contestable',
        'NetMetered',
        'Notes',
        'Migrated',
        'MemberConsumerId',
        'DistributionAccountCode',
        'DownloadedByDisco',
        'Item1', // coop consumption = Yes
        'MeterNumber',
        'CheckBounceHistory',
        'Zone',
        'Lifeliner',
        'LifelinerDateApplied',
        'LifelinerDateExpire',
        'SeniorCitizenDateApplied',
        'SeniorCitizenDateExpire',
        'AdvancedMaterialDeposit',
        'CustomerDeposit',
        'AdvancedMaterialDepositStatus', // PAUSED, DEDUCTING
        'CustomerDepositStatus', // PAUSED, DEDUCTING
        'CustomerDepositYearRenewed',
        'CustomerDepositOriginalAmount',
        'CustomerDepositLastRenewed',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceAccountName' => 'string',
        'Town' => 'string',
        'Barangay' => 'string',
        'Purok' => 'string',
        'AccountType' => 'string',
        'AccountStatus' => 'string',
        'ContactNumber' => 'string',
        'EmailAddress' => 'string',
        'ServiceConnectionId' => 'string',
        'MeterDetailsId' => 'string',
        'TransformerDetailsId' => 'string',
        'PoleNumber' => 'string',
        'AreaCode' => 'string',
        'BlockCode' => 'string',
        'SequenceCode' => 'string',
        'Feeder' => 'string',
        'ComputeType' => 'string',
        'Organization' => 'string',
        'OrganizationParentAccount' => 'string',
        'GPSMeter' => 'string',
        'AccountCount' => 'string',
        'OldAccountNo' => 'string',
        'UserId' => 'string',
        'MeterReader' => 'string',
        'GroupCode' => 'string',
        'ForDistribution' => 'string',
        'Multiplier' => 'string',
        'Coreloss' => 'string',
        'Main' => 'string',
        'Evat5Percent' => 'string',
        'Ewt2Percent' => 'string',
        'ConnectionDate' => 'string',
        'LatestReadingDate' => 'string',
        'DateDisconnected' => 'string',
        'DateTransfered' => 'string',
        'SeniorCitizen' => 'string',
        'AccountPaymentType' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'AccountRetention' => 'string',
        'AccountExpiration' => 'string',
        'DurationInMonths' => 'string',
        'Contestable' => 'string',
        'NetMetered' => 'string',
        'Notes' => 'string',
        'Migrated' => 'string',
        'MemberConsumerId' => 'string',
        'DistributionAccountCode' => 'string',
        'DownloadedByDisco' => 'string',
        'Item1' => 'string',
        'MeterNumber' => 'string',
        'CheckBounceHistory' => 'string',
        'Zone' => 'string',
        'Lifeliner' => 'string',
        'LifelinerDateApplied' => 'string',
        'LifelinerDateExpire' => 'string',
        'SeniorCitizenDateApplied' => 'string',
        'SeniorCitizenDateExpire' => 'string',
        'AdvancedMaterialDeposit' => 'string',
        'CustomerDeposit' => 'string',
        'AdvancedMaterialDepositStatus' => 'string',
        'CustomerDepositStatus' => 'string',
        'CustomerDepositYearRenewed' => 'string',
        'CustomerDepositOriginalAmount' => 'string',
        'CustomerDepositLastRenewed' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceAccountName' => 'required|string|max:600',
        'Town' => 'nullable|string|max:50',
        'Barangay' => 'nullable|string|max:50',
        'Purok' => 'nullable|string|max:200',
        'AccountType' => 'nullable|string|max:100',
        'AccountStatus' => 'nullable|string|max:50',
        'ContactNumber' => 'nullable|string|max:60',
        'EmailAddress' => 'nullable|string|max:60',
        'ServiceConnectionId' => 'nullable|string|max:30',
        'MeterDetailsId' => 'nullable|string|max:50',
        'TransformerDetailsId' => 'nullable|string|max:50',
        'PoleNumber' => 'nullable|string|max:255',
        'AreaCode' => 'nullable|string|max:50',
        'BlockCode' => 'nullable|string|max:50',
        'SequenceCode' => 'nullable|string|max:50',
        'Feeder' => 'nullable|string|max:50',
        'ComputeType' => 'nullable|string|max:20',
        'Organization' => 'nullable|string|max:30',
        'OrganizationParentAccount' => 'nullable|string|max:30',
        'GPSMeter' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AccountCount' => 'nullable|string',
        'OldAccountNo' => 'nullable|string',
        'UserId' => 'nullable|string',
        'MeterReader' => 'nullable|string',
        'GroupCode' => 'nullable|string',
        'ForDistribution' => 'nullable|string',
        'Multiplier' => 'nullable|string',
        'Coreloss' => 'nullable|string',
        'Main' => 'nullable|string',
        'Evat5Percent' => 'nullable|string',
        'Ewt2Percent' => 'nullable|string',
        'ConnectionDate' => 'nullable',
        'LatestReadingDate' => 'nullable',
        'DateDisconnected' => 'nullable',
        'DateTransfered' => 'nullable',
        'SeniorCitizen' => 'nullable|string',
        'AccountPaymentType' => 'nullable|string',
        'Latitude' => 'nullable|string',
        'Longitude' => 'nullable|string',
        'AccountRetention' => 'nullable|string',
        'AccountExpiration' => 'nullable|string',
        'DurationInMonths' => 'nullable|string',
        'Contestable' => 'nullable|string',
        'NetMetered' => 'nullable|string',
        'Notes' => 'nullable|string',
        'Migrated' => 'nullable|string',
        'MemberConsumerId' => 'nullable|string',
        'DistributionAccountCode' => 'nullable|string',
        'DownloadedByDisco' => 'nullable|string',
        'Item1' => 'nullable|string',
        'MeterNumber' => 'nullable|string',
        'CheckBounceHistory' => 'nullable|string',
        'Zone' => 'nullable|string',
        'Lifeliner' => 'nullable|string',
        'LifelinerDateApplied' => 'nullable|string',
        'LifelinerDateExpire' => 'nullable|string',
        'SeniorCitizenDateApplied' => 'nullable|string',
        'SeniorCitizenDateExpire' => 'nullable|string',
        'AdvancedMaterialDeposit' => 'nullable|string',
        'CustomerDeposit' => 'nullable|string',
        'AdvancedMaterialDepositStatus' => 'nullable|string',
        'CustomerDepositStatus' => 'nullable|string',
        'CustomerDepositYearRenewed' => 'nullable|string',
        'CustomerDepositOriginalAmount' => 'nullable|string',
        'CustomerDepositLastRenewed' => 'nullable|string',
    ];

    public static function getAddress($serviceAccount) {
        if ($serviceAccount->Purok==null && ($serviceAccount->Barangay!=null && $serviceAccount->Town!=null)) {
            return $serviceAccount->Barangay . ', ' . $serviceAccount->Town;
        } elseif($serviceAccount->Purok!=null && ($serviceAccount->Barangay!=null && $serviceAccount->Town!=null)) {
            return $serviceAccount->Purok . ', ' . $serviceAccount->Barangay . ', ' . $serviceAccount->Town;
        } elseif($serviceAccount->Barangay == null) {
            return $serviceAccount->Purok;
        }
    }

    public static function getLatitude($object) {
        if ($object != null) {
            $splitted = explode(",", $object);
            if ($splitted != null && $splitted[0] != null) {
                return $splitted[0];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function getLongitude($object) {
        if ($object != null) {
            $splitted = explode(",", $object);
            if ($splitted != null && $splitted[1] != null) {
                return $splitted[1];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function getCustomerDepositInterest($account) {
        $ogAmount = floatval($account->CustomerDepositOriginalAmount);

        $startDay = $account->CustomerDepositLastRenewed != null ? $account->CustomerDepositLastRenewed : $account->ConnectionDate;
        $startDay = $startDay != null ? strtotime($startDay) : time();
        $endDay = time();
        $dateDif = $endDay - $startDay;

        $days = round($dateDif / (60 * 60 * 24));
        $interestPercent = 0.0005;
        $whTax = .2;

        $interest = $ogAmount * ($days/365) * $interestPercent;

        return $interest - ($interest * $whTax);
    }
}
