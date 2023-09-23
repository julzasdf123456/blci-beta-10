<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BAPAAdjustmentDetails
 * @package App\Models
 * @version May 25, 2022, 11:05 am PST
 *
 * @property string $AccountNumber
 * @property string $BillId
 * @property string $DiscountPercentage
 * @property string $DiscountAmount
 * @property string $BAPAName
 * @property string $ServicePeriod
 */
class BAPAAdjustmentDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_BAPAAdjustmentDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'BillId',
        'DiscountPercentage',
        'DiscountAmount',
        'BAPAName',
        'ServicePeriod'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'BillId' => 'string',
        'DiscountPercentage' => 'string',
        'DiscountAmount' => 'string',
        'BAPAName' => 'string',
        'ServicePeriod' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'BillId' => 'nullable|string|max:255',
        'DiscountPercentage' => 'nullable|string|max:255',
        'DiscountAmount' => 'nullable|string|max:255',
        'BAPAName' => 'nullable|string|max:500',
        'ServicePeriod' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
