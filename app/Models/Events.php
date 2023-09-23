<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Events
 * @package App\Models
 * @version November 16, 2022, 7:41 pm PST
 *
 * @property string $EventTitle
 * @property string $EventDescription
 * @property string|\Carbon\Carbon $EventStart
 * @property string|\Carbon\Carbon $EventEnd
 * @property string|\Carbon\Carbon $RegistrationStart
 * @property string|\Carbon\Carbon $RegistrationEnd
 * @property string $UserId
 */
class Events extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Events';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'EventTitle',
        'EventDescription',
        'EventStart',
        'EventEnd',
        'RegistrationStart',
        'RegistrationEnd',
        'UserId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'EventTitle' => 'string',
        'EventDescription' => 'string',
        'EventStart' => 'datetime',
        'EventEnd' => 'datetime',
        'RegistrationStart' => 'datetime',
        'RegistrationEnd' => 'datetime',
        'UserId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'EventTitle' => 'required|string|max:300',
        'EventDescription' => 'nullable|string|max:2000',
        'EventStart' => 'nullable',
        'EventEnd' => 'nullable',
        'RegistrationStart' => 'nullable',
        'RegistrationEnd' => 'nullable',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
