<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DisconnectionHistory
 * @package App\Models
 * @version February 21, 2022, 8:54 am PST
 *
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $Latitude
 * @property string $Longitude
 * @property string $BillId
 * @property string $DisconnectionPayment
 * @property string $Status
 * @property string $UserId
 * @property string $Notes
 */
class DisconnectionHistory extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Disconnection_History';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ServicePeriod',
        'Latitude',
        'Longitude',
        'BillId',
        'DisconnectionPayment',
        'Status',
        'UserId',
        'Notes',
        'DateDisconnected',
        'TimeDisconnected'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'date',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'BillId' => 'string',
        'DisconnectionPayment' => 'string',
        'Status' => 'string',
        'UserId' => 'string',
        'Notes' => 'string',
        'DateDisconnected' => 'string',
        'TimeDisconnected' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'Latitude' => 'nullable|string|max:255',
        'Longitude' => 'nullable|string|max:255',
        'BillId' => 'nullable|string|max:255',
        'DisconnectionPayment' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DateDisconnected' => 'string|nullable',
        'TimeDisconnected' => 'string|nullable'
    ];

    public static function noOfDaysTillNotice() {
        return 9;
    }

    public static function noOfDaysTillDisconnection() {
        return 11;
    }
}
