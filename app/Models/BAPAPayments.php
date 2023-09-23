<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BAPAPayments
 * @package App\Models
 * @version April 11, 2022, 3:30 pm PST
 *
 * @property string $BAPAName
 * @property string $ServicePeriod
 * @property string $ORNumber
 * @property string $ORDate
 * @property string $SubTotal
 * @property string $TwoPercentDiscount
 * @property string $FivePercentDiscount
 * @property string $AdditionalCharges
 * @property string $Deductions
 * @property string $VAT
 * @property string $Total
 * @property string $Teller
 * @property string $NoOfConsumersPaid
 * @property string $Status
 * @property string $Notes
 */
class BAPAPayments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_BAPAPayments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BAPAName',
        'ServicePeriod',
        'ORNumber',
        'ORDate',
        'SubTotal',
        'TwoPercentDiscount',
        'FivePercentDiscount',
        'AdditionalCharges',
        'Deductions',
        'VAT',
        'Total',
        'Teller',
        'NoOfConsumersPaid',
        'Status',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BAPAName' => 'string',
        'ServicePeriod' => 'date',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'SubTotal' => 'string',
        'TwoPercentDiscount' => 'string',
        'FivePercentDiscount' => 'string',
        'AdditionalCharges' => 'string',
        'Deductions' => 'string',
        'VAT' => 'string',
        'Total' => 'string',
        'Teller' => 'string',
        'NoOfConsumersPaid' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BAPAName' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'ORNumber' => 'nullable|string|max:255',
        'ORDate' => 'nullable',
        'SubTotal' => 'nullable|string|max:255',
        'TwoPercentDiscount' => 'nullable|string|max:255',
        'FivePercentDiscount' => 'nullable|string|max:255',
        'AdditionalCharges' => 'nullable|string|max:255',
        'Deductions' => 'nullable|string|max:255',
        'VAT' => 'nullable|string|max:255',
        'Total' => 'nullable|string|max:255',
        'Teller' => 'nullable|string|max:255',
        'NoOfConsumersPaid' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
