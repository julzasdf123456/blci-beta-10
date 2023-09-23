<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PreDefinedMaterials
 * @package App\Models
 * @version October 4, 2021, 9:07 am PST
 *
 * @property string $NEACode
 * @property string $Quantity
 * @property string $Options
 * @property string $ApplicationType
 * @property string $Notes
 */
class PreDefinedMaterials extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_PreDefinedMaterials';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'NEACode',
        'Quantity',
        'Options',
        'ApplicationType',
        'Notes',
        'LaborPercentage',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'NEACode' => 'string',
        'Quantity' => 'string',
        'Options' => 'string',
        'ApplicationType' => 'string',
        'Notes' => 'string',
        'LaborPercentage' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'NEACode' => 'nullable|string|max:50',
        'Quantity' => 'nullable|string|max:20',
        'Options' => 'nullable|string|max:255',
        'ApplicationType' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'LaborPercentage' => 'nullable|string',
    ];

    
}
