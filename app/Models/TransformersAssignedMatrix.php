<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TransformersAssignedMatrix
 * @package App\Models
 * @version September 21, 2021, 10:05 am PST
 *
 * @property string $ServiceConnectionId
 * @property string $MaterialsId
 * @property string $Quantity
 */
class TransformersAssignedMatrix extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_TransformersAssignedMatrix';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'MaterialsId',
        'Quantity',
        'Type',
        'Amount',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'MaterialsId' => 'string',
        'Quantity' => 'string',
        'Type' => 'string',
        'Amount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'MaterialsId' => 'nullable|string|max:255',
        'Quantity' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Type' => 'nullable|string',
        'Amount' => 'nullable|string',
    ];

    
}
