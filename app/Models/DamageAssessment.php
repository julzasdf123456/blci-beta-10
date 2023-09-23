<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DamageAssessment
 * @package App\Models
 * @version January 12, 2022, 8:13 am PST
 *
 * @property string $Type
 * @property string $ObjectName
 * @property string $Feeder
 * @property string $Town
 * @property string $Status
 * @property string $Notes
 * @property string|\Carbon\Carbon $DateFixed
 * @property string $CrewAssigned
 * @property string $Latitude
 * @property string $Longitude
 */
class DamageAssessment extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_DamageAssessment';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Type',
        'ObjectName',
        'Feeder',
        'Town',
        'Status',
        'Notes',
        'DateFixed',
        'CrewAssigned',
        'Latitude',
        'Longitude'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Type' => 'string',
        'ObjectName' => 'string',
        'Feeder' => 'string',
        'Town' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'DateFixed' => 'datetime',
        'CrewAssigned' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'Type' => 'nullable|string|max:255',
        'ObjectName' => 'nullable|string|max:255',
        'Feeder' => 'nullable|string|max:255',
        'Town' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:3000',
        'DateFixed' => 'nullable',
        'CrewAssigned' => 'nullable|string|max:500',
        'Latitude' => 'nullable|string|max:255',
        'Longitude' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
