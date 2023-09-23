<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CostCenters
 * @package App\Models
 * @version June 27, 2023, 9:26 am PST
 *
 * @property string $CostCode
 * @property string $CostName
 * @property string $CostDepartment
 */
class CostCenters extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_CostCenters';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "sqlsrv";

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'CostCode',
        'CostName',
        'CostDepartment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'CostCode' => 'string',
        'CostName' => 'string',
        'CostDepartment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'CostCode' => 'nullable|string|max:255',
        'CostName' => 'nullable|string|max:1500',
        'CostDepartment' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
