<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillingMeters
 * @package App\Models
 * @version November 22, 2021, 11:39 am PST
 *
 * @property string $ServiceAccountId
 * @property string $SerialNumber
 * @property string $SealNumber
 * @property string $Brand
 * @property string $Model
 * @property string $Multiplier
 * @property string $Status
 * @property string $ConnectionDate
 * @property string|\Carbon\Carbon $LatestReadingDate
 * @property string $DateDisconnected
 * @property string $DateTransfered
 */
class BillingMeters extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_Meters';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceAccountId',
        'SerialNumber',
        'SealNumber',
        'Brand',
        'Model',
        'Multiplier',
        'Status',
        'ConnectionDate',
        'LatestReadingDate',
        'DateDisconnected',
        'DateTransfered',
        'InitialReading',
        'LatestReading'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceAccountId' => 'string',
        'SerialNumber' => 'string',
        'SealNumber' => 'string',
        'Brand' => 'string',
        'Model' => 'string',
        'Multiplier' => 'string',
        'Status' => 'string',
        'ConnectionDate' => 'date',
        'LatestReadingDate' => 'datetime',
        'DateDisconnected' => 'date',
        'DateTransfered' => 'date',
        'InitialReading' => 'string',
        'LatestReading' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceAccountId' => 'nullable|string|max:120',
        'SerialNumber' => 'nullable|string|max:100',
        'SealNumber' => 'nullable|string|max:120',
        'Brand' => 'nullable|string|max:180',
        'Model' => 'nullable|string|max:180',
        'Multiplier' => 'nullable|string|max:10',
        'Status' => 'nullable|string|max:60',
        'ConnectionDate' => 'nullable',
        'LatestReadingDate' => 'nullable',
        'DateDisconnected' => 'nullable',
        'DateTransfered' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'InitialReading' => 'string|nullable',
        'LatestReading' => 'string|nullable'
    ];

    
}
