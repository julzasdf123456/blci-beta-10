<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillOfMaterialsDetails
 * @package App\Models
 * @version September 16, 2021, 8:30 am PST
 *
 * @property string $BillOfMaterialsId
 * @property string $NeaCode
 * @property string $Description
 * @property string $Rate
 * @property string $Quantity
 * @property string $Amount
 */
class BillOfMaterialsDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_BillOfMaterialsDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BillOfMaterialsId',
        'NeaCode',
        'Description',
        'Rate',
        'Quantity',
        'Amount'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BillOfMaterialsId' => 'string',
        'NeaCode' => 'string',
        'Description' => 'string',
        'Rate' => 'string',
        'Quantity' => 'string',
        'Amount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BillOfMaterialsId' => 'nullable|string|max:255',
        'NeaCode' => 'nullable|string|max:255',
        'Description' => 'nullable|string|max:1000',
        'Rate' => 'nullable|string|max:50',
        'Quantity' => 'nullable|string|max:15',
        'Amount' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
