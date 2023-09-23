<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionMatPayments
 * @package App\Models
 * @version August 17, 2021, 1:14 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $Material
 * @property string $Quantity
 * @property string $Vat
 * @property string $Total
 */
class ServiceConnectionMatPayments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionMaterialPayments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Material',
        'Quantity',
        'Vat',
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
        'Material' => 'string',
        'Quantity' => 'string',
        'Vat' => 'string',
        'Total' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'Material' => 'required|string|max:40',
        'Quantity' => 'nullable|string|max:20',
        'Vat' => 'nullable|string|max:100',
        'Total' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
