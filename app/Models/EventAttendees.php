<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EventAttendees
 * @package App\Models
 * @version November 16, 2022, 7:42 pm PST
 *
 * @property string $EventId
 * @property string $HaveAttended
 * @property string $AccountNumber
 * @property string $Name
 * @property string $Address
 * @property string $RegisteredAt
 * @property string $RegistationMedium
 * @property string $UserId
 * @property string $Notes
 */
class EventAttendees extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_EventAttendees';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EventId',
        'HaveAttended',
        'AccountNumber',
        'Name',
        'Address',
        'RegisteredAt',
        'RegistationMedium',
        'UserId',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'EventId' => 'string',
        'HaveAttended' => 'string',
        'AccountNumber' => 'string',
        'Name' => 'string',
        'Address' => 'string',
        'RegisteredAt' => 'string',
        'RegistationMedium' => 'string',
        'UserId' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EventId' => 'required|string|max:255',
        'HaveAttended' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'Name' => 'nullable|string|max:400',
        'Address' => 'nullable|string|max:550',
        'RegisteredAt' => 'nullable|string|max:255',
        'RegistationMedium' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
