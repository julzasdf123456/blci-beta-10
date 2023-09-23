<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MaterialsMatrix
 * @package App\Models
 * @version September 16, 2021, 8:29 am PST
 *
 * @property string $StructureId
 * @property string $MaterialsId
 * @property string $Quantity
 */
class MaterialsMatrix extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MaterialsMatrix';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'StructureId',
        'MaterialsId',
        'Quantity'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'StructureId' => 'string',
        'MaterialsId' => 'string',
        'Quantity' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'StructureId' => 'nullable|string|max:255',
        'MaterialsId' => 'nullable|string|max:255',
        'Quantity' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
