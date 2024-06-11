<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MeterInstallation
 * @package App\Models
 * @version July 3, 2023, 1:49 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $Type
 * @property string $NewMeterNumber
 * @property string $NewMeterBrand
 * @property string $NewMeterSize
 * @property string $NewMeterType
 * @property string $NewMeterAmperes
 * @property number $NewMeterInitialReading
 * @property string $NewMeterLineToNeutral
 * @property string $NewMeterLineToGround
 * @property string $NewMeterNeutralToGround
 * @property string $DateInstalled
 * @property string $NewMeterMultiplier
 * @property string $TransfomerCapacity
 * @property string $TransformerID
 * @property string $PoleID
 * @property string $CTSerialNumber
 * @property string $NewMeterRemarks
 * @property string $OldMeterNumber
 * @property string $OldMeterBrand
 * @property string $OldMeterSize
 * @property string $OldMeterType
 * @property string $DateRemoved
 * @property string $ReasonForChanging
 * @property string $OldMeterMultiplier
 * @property string $OldMeterRemarks
 * @property string $InstalledBy
 * @property string $CheckedBy
 * @property string $Witness
 * @property string $BLCIRepresentative
 * @property string $ApprovedBy
 * @property string $RemovedBy
 * @property string $CustomerSignature
 * @property string $WitnessSignature
 * @property string $InstalledBySignature
 * @property string $ApprovedBySignature
 * @property string $CheckedBySignature
 * @property string $BLCIRepresentativeSignature
 */
class MeterInstallation extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MeterInstallation';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrv";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Type',
        'NewMeterNumber',
        'NewMeterBrand',
        'NewMeterSize',
        'NewMeterType',
        'NewMeterAmperes',
        'NewMeterInitialReading',
        'NewMeterLineToNeutral',
        'NewMeterLineToGround',
        'NewMeterNeutralToGround',
        'DateInstalled',
        'NewMeterMultiplier',
        'TransfomerCapacity',
        'TransformerID',
        'PoleID',
        'CTSerialNumber',
        'NewMeterRemarks',
        'OldMeterNumber',
        'OldMeterBrand',
        'OldMeterSize',
        'OldMeterType',
        'DateRemoved',
        'ReasonForChanging',
        'OldMeterMultiplier',
        'OldMeterRemarks',
        'InstalledBy',
        'CheckedBy',
        'Witness',
        'BLCIRepresentative',
        'ApprovedBy',
        'RemovedBy',
        'CustomerSignature',
        'WitnessSignature',
        'InstalledBySignature',
        'ApprovedBySignature',
        'CheckedBySignature',
        'BLCIRepresentativeSignature'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Type' => 'string',
        'NewMeterNumber' => 'string',
        'NewMeterBrand' => 'string',
        'NewMeterSize' => 'string',
        'NewMeterType' => 'string',
        'NewMeterAmperes' => 'string',
        'NewMeterInitialReading' => 'string',
        'NewMeterLineToNeutral' => 'string',
        'NewMeterLineToGround' => 'string',
        'NewMeterNeutralToGround' => 'string',
        'DateInstalled' => 'string',
        'NewMeterMultiplier' => 'string',
        'TransfomerCapacity' => 'string',
        'TransformerID' => 'string',
        'PoleID' => 'string',
        'CTSerialNumber' => 'string',
        'NewMeterRemarks' => 'string',
        'OldMeterNumber' => 'string',
        'OldMeterBrand' => 'string',
        'OldMeterSize' => 'string',
        'OldMeterType' => 'string',
        'DateRemoved' => 'string',
        'ReasonForChanging' => 'string',
        'OldMeterMultiplier' => 'string',
        'OldMeterRemarks' => 'string',
        'InstalledBy' => 'string',
        'CheckedBy' => 'string',
        'Witness' => 'string',
        'BLCIRepresentative' => 'string',
        'ApprovedBy' => 'string',
        'RemovedBy' => 'string',
        'CustomerSignature' => 'string',
        'WitnessSignature' => 'string',
        'InstalledBySignature' => 'string',
        'ApprovedBySignature' => 'string',
        'CheckedBySignature' => 'string',
        'BLCIRepresentativeSignature' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServiceConnectionId' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:255',
        'NewMeterNumber' => 'nullable|string|max:300',
        'NewMeterBrand' => 'nullable|string|max:300',
        'NewMeterSize' => 'nullable|string|max:300',
        'NewMeterType' => 'nullable|string|max:300',
        'NewMeterAmperes' => 'nullable|string|max:300',
        'NewMeterInitialReading' => 'nullable|numeric',
        'NewMeterLineToNeutral' => 'nullable|string|max:300',
        'NewMeterLineToGround' => 'nullable|string|max:300',
        'NewMeterNeutralToGround' => 'nullable|string|max:300',
        'DateInstalled' => 'nullable',
        'NewMeterMultiplier' => 'nullable|string|max:300',
        'TransfomerCapacity' => 'nullable|string|max:300',
        'TransformerID' => 'nullable|string|max:300',
        'PoleID' => 'nullable|string|max:300',
        'CTSerialNumber' => 'nullable|string|max:300',
        'NewMeterRemarks' => 'nullable|string|max:2500',
        'OldMeterNumber' => 'nullable|string|max:300',
        'OldMeterBrand' => 'nullable|string|max:300',
        'OldMeterSize' => 'nullable|string|max:300',
        'OldMeterType' => 'nullable|string|max:300',
        'DateRemoved' => 'nullable',
        'ReasonForChanging' => 'nullable|string|max:1500',
        'OldMeterMultiplier' => 'nullable|string|max:300',
        'OldMeterRemarks' => 'nullable|string|max:2500',
        'InstalledBy' => 'nullable|string|max:500',
        'CheckedBy' => 'nullable|string|max:500',
        'Witness' => 'nullable|string|max:500',
        'BLCIRepresentative' => 'nullable|string|max:500',
        'ApprovedBy' => 'nullable|string|max:500',
        'RemovedBy' => 'nullable|string|max:500',
        'CustomerSignature' => 'nullable|string|max:255',
        'WitnessSignature' => 'nullable|string|max:255',
        'InstalledBySignature' => 'nullable|string|max:255',
        'ApprovedBySignature' => 'nullable|string|max:255',
        'CheckedBySignature' => 'nullable|string|max:255',
        'BLCIRepresentativeSignature' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
