<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ChangeMeterLogs
 * @package App\Models
 * @version April 18, 2022, 5:07 pm PST
 *
 * @property string $AccountNumber
 * @property string $OldMeterSerial
 * @property string $NewMeterSerial
 * @property string $PullOutReading
 * @property string $AdditionalKwhForNextBilling
 * @property string $Status
 */
class ChangeMeterLogs extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_ChangeMeterLogs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'OldMeterSerial',
        'NewMeterSerial',
        'PullOutReading',
        'AdditionalKwhForNextBilling',
        'Status',
        'ServicePeriod',
        'NewMeterStartKwh'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'OldMeterSerial' => 'string',
        'NewMeterSerial' => 'string',
        'PullOutReading' => 'string',
        'AdditionalKwhForNextBilling' => 'string',
        'Status' => 'string',
        'ServicePeriod' => 'string',
        'NewMeterStartKwh' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'OldMeterSerial' => 'nullable|string|max:255',
        'NewMeterSerial' => 'nullable|string|max:255',
        'PullOutReading' => 'nullable|string|max:255',
        'AdditionalKwhForNextBilling' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ServicePeriod' => 'nullable|string',
        'NewMeterStartKwh' => 'nullable|string',
    ];

    
}
