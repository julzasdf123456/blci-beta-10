<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PreDefinedMaterialsMatrix
 * @package App\Models
 * @version October 5, 2021, 9:37 am PST
 *
 * @property string $ServiceConnectionId
 * @property string $NEACode
 * @property string $Description
 * @property string $Quantity
 * @property string $Options
 * @property string $ApplicationType
 * @property string $Cost
 * @property string $LaborCost
 * @property string $Notes
 * @property string $LaborPercentage
 */
class PreDefinedMaterialsMatrix extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_PreDefinedMaterialsMatrix';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'NEACode',
        'Description',
        'Quantity',
        'Options',
        'ApplicationType',
        'Cost',
        'LaborCost',
        'Notes',
        'LaborPercentage',
        'Amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'NEACode' => 'string',
        'Description' => 'string',
        'Quantity' => 'string',
        'Options' => 'string',
        'ApplicationType' => 'string',
        'Cost' => 'string',
        'LaborCost' => 'string',
        'Notes' => 'string',
        'LaborPercentage' => 'string',
        'Amount' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'NEACode' => 'nullable|string|max:50',
        'Description' => 'nullable|string|max:1000',
        'Quantity' => 'nullable|string|max:20',
        'Options' => 'nullable|string|max:255',
        'ApplicationType' => 'nullable|string|max:255',
        'Cost' => 'nullable|string|max:255',
        'LaborCost' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'LaborPercentage' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Amount' => 'string|nullable',
    ];

    
}
