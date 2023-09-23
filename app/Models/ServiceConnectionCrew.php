<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionCrew
 * @package App\Models
 * @version September 8, 2021, 8:25 am PST
 *
 * @property string $StationName
 * @property string $CrewLeader
 * @property string $Members
 * @property string $Notes
 */
class ServiceConnectionCrew extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionCrew';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'StationName',
        'CrewLeader',
        'Members',
        'Notes',
        'Office',
        'Grouping'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'StationName' => 'string',
        'CrewLeader' => 'string',
        'Members' => 'string',
        'Notes' => 'string',
        'Office' => 'string',
        'Grouping' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'StationName' => 'nullable|string|max:140',
        'CrewLeader' => 'nullable|string|max:300',
        'Members' => 'nullable|string|max:1500',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Office' => 'string|nullable',
        'Grouping' => 'string|nullable'
    ];

    
}
