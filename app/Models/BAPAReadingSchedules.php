<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BAPAReadingSchedules
 * @package App\Models
 * @version April 7, 2022, 4:37 pm PST
 *
 * @property string $ServicePeriod
 * @property string $Town
 * @property string $BAPAName
 * @property string $Status
 * @property string $DownloadedBy
 */
class BAPAReadingSchedules extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_BAPAReadingSchedule';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServicePeriod',
        'Town',
        'BAPAName',
        'Status',
        'DownloadedBy'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServicePeriod' => 'string',
        'Town' => 'string',
        'BAPAName' => 'string',
        'Status' => 'string',
        'DownloadedBy' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServicePeriod' => 'nullable',
        'Town' => 'nullable|string|max:255',
        'BAPAName' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'DownloadedBy' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
