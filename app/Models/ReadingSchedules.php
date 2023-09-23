<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ReadingSchedules
 * @package App\Models
 * @version January 17, 2022, 9:45 am PST
 *
 * @property string $AreaCode
 * @property string $GroupCode
 * @property string $ServicePeriod
 * @property string $ScheduledDate
 * @property string $MeterReader
 */
class ReadingSchedules extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_ReadingSchedules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AreaCode',
        'GroupCode',
        'ServicePeriod',
        'ScheduledDate',
        'MeterReader',
        'Status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AreaCode' => 'string',
        'GroupCode' => 'string',
        'ServicePeriod' => 'string',
        'ScheduledDate' => 'string',
        'MeterReader' => 'string',
        'Status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AreaCode' => 'nullable|string|max:255',
        'GroupCode' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'ScheduledDate' => 'nullable',
        'MeterReader' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string'
    ];

}
