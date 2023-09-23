<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionLgLoadInsp
 * @package App\Models
 * @version September 15, 2021, 4:41 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $Assessment
 * @property string $DateOfInspection
 * @property string $Notes
 */
class ServiceConnectionLgLoadInsp extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_LargeLoadInspections';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Assessment',
        'DateOfInspection',
        'Notes',
        'Options',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Assessment' => 'string',
        'DateOfInspection' => 'date',
        'Notes' => 'string',
        'Options' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'Assessment' => 'nullable|string|max:255',
        'DateOfInspection' => 'nullable',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Options' => 'string|nullable'
    ];

    
}
