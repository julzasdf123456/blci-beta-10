<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillOfMaterialsIndex
 * @package App\Models
 * @version September 16, 2021, 8:30 am PST
 *
 * @property string $ServiceConnectionId
 * @property string $Date
 * @property string $SubTotal
 * @property string $LaborCost
 * @property string $Others
 * @property string $Total
 */
class BillOfMaterialsIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_BillOfMaterialsIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Date',
        'SubTotal',
        'LaborCost',
        'Others',
        'Total'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Date' => 'date',
        'SubTotal' => 'string',
        'LaborCost' => 'string',
        'Others' => 'string',
        'Total' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'Date' => 'nullable',
        'SubTotal' => 'nullable|string|max:100',
        'LaborCost' => 'nullable|string|max:100',
        'Others' => 'nullable|string|max:100',
        'Total' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
