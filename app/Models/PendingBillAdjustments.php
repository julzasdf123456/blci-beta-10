<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PendingBillAdjustments
 * @package App\Models
 * @version March 22, 2022, 1:31 pm PST
 *
 * @property string $ReadingId
 * @property string $KwhUsed
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $Confirmed
 * @property string $ReadDate
 */
class PendingBillAdjustments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_PendingBillAdjustments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ReadingId',
        'KwhUsed',
        'AccountNumber',
        'ServicePeriod',
        'Confirmed',
        'ReadDate',
        'UserId',
        'Office'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ReadingId' => 'string',
        'KwhUsed' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'date',
        'Confirmed' => 'string',
        'ReadDate' => 'string',
        'UserId' => 'string',
        'Office' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ReadingId' => 'nullable|string|max:255',
        'KwhUsed' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'Confirmed' => 'nullable|string|max:255',
        'ReadDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',        
        'UserId' => 'string|nullable',        
        'Office' => 'string|nullable'
    ];

    
}
