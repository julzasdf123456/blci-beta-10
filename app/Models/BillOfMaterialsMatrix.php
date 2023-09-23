<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillOfMaterialsMatrix
 * @package App\Models
 * @version September 17, 2021, 3:39 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $StructureAssigningId
 * @property string $StructureId
 * @property string $MaterialsId
 * @property string $Quantity
 */
class BillOfMaterialsMatrix extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_BillOfMaterialsMatrix';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'StructureAssigningId',
        'StructureId',
        'MaterialsId',
        'Quantity',
        'StructureType',
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
        'StructureAssigningId' => 'string',
        'StructureId' => 'string',
        'MaterialsId' => 'string',
        'Quantity' => 'string',
        'StructureType' => 'string',
        'Amount' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'nullable|string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'StructureAssigningId' => 'nullable|string|max:255',
        'StructureId' => 'nullable|string|max:255',
        'MaterialsId' => 'nullable|string|max:255',
        'Quantity' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'StructureType' => 'nullable|string',
        'Amount' => 'nullable|string'
    ];

    
}
