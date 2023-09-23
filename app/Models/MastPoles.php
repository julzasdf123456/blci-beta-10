<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MastPoles
 * @package App\Models
 * @version June 11, 2022, 6:38 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $Latitude
 * @property string $Longitude
 * @property string|\Carbon\Carbon $DateTimeTaken
 * @property string $PoleRemarks
 */
class MastPoles extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MastPoles';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Latitude',
        'Longitude',
        'DateTimeTaken',
        'PoleRemarks'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'DateTimeTaken' => 'datetime',
        'PoleRemarks' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'Latitude' => 'nullable|string|max:255',
        'Longitude' => 'nullable|string|max:255',
        'DateTimeTaken' => 'nullable',
        'PoleRemarks' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
