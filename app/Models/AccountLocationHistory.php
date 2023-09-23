<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AccountLocationHistory
 * @package App\Models
 * @version June 13, 2022, 8:40 am PST
 *
 * @property string $AccountNumber
 * @property string $Town
 * @property string $Barangay
 * @property string $Purok
 * @property string $AreaCode
 * @property string $SequenceCode
 * @property string $MeterReader
 * @property string $ServiceConnectionId
 * @property string $RelocationDate
 */
class AccountLocationHistory extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_AccountLocationHistory';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'Town',
        'Barangay',
        'Purok',
        'AreaCode',
        'SequenceCode',
        'MeterReader',
        'ServiceConnectionId',
        'RelocationDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'Town' => 'string',
        'Barangay' => 'string',
        'Purok' => 'string',
        'AreaCode' => 'string',
        'SequenceCode' => 'string',
        'MeterReader' => 'string',
        'ServiceConnectionId' => 'string',
        'RelocationDate' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'Town' => 'nullable|string|max:255',
        'Barangay' => 'nullable|string|max:255',
        'Purok' => 'nullable|string|max:255',
        'AreaCode' => 'nullable|string|max:255',
        'SequenceCode' => 'nullable|string|max:255',
        'MeterReader' => 'nullable|string|max:255',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'RelocationDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
