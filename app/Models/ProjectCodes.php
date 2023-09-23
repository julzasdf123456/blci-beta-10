<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProjectCodes
 * @package App\Models
 * @version June 27, 2023, 9:33 am PST
 *
 * @property string $ProjectCode
 * @property string $ProjectDescription
 * @property string $ProjectCategory
 */
class ProjectCodes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ProjectCodes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrv";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ProjectCode',
        'ProjectDescription',
        'ProjectCategory'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ProjectCode' => 'string',
        'ProjectDescription' => 'string',
        'ProjectCategory' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ProjectCode' => 'nullable|string|max:255',
        'ProjectDescription' => 'nullable|string|max:255',
        'ProjectCategory' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
