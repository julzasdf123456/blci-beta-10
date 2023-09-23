<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionTimeframes
 * @package App\Models
 * @version August 19, 2021, 6:35 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $UserId
 * @property string $Status
 * @property string $Notes
 */
class ServiceConnectionTimeframes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionTimeframes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'UserId',
        'Status',
        'Notes',
        'LogFor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'UserId' => 'string',
        'Status' => 'string',
        'Notes' => 'string',
        'LogFor' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'UserId' => 'required|string|max:255',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'LogFor' => 'nullable|string',
    ];

    
}
