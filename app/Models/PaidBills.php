<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaidBills
 * @package App\Models
 * @version February 11, 2022, 8:16 am PST
 *
 * @property string $BillNumber
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $ORNumber
 * @property string $ORDate
 * @property string $DCRNumber
 * @property string $KwhUsed
 * @property string $Teller
 * @property string $OfficeTransacted
 * @property string $PostingDate
 * @property time $PostingTime
 * @property string $Surcharge
 * @property string $Form2307TwoPercent
 * @property string $Form2307FivePercent
 * @property string $AdditionalCharges
 * @property string $Deductions
 * @property string $NetAmount
 * @property string $Source
 * @property string $ObjectSourceId
 * @property string $UserId
 */
class PaidBills extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_PaidBills';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BillNumber',
        'AccountNumber',
        'ServicePeriod',
        'ORNumber',
        'ORDate',
        'DCRNumber',
        'KwhUsed',
        'Teller',
        'OfficeTransacted',
        'PostingDate',
        'PostingTime',
        'Surcharge',
        'Form2307TwoPercent',
        'Form2307FivePercent',
        'AdditionalCharges',
        'Deductions',
        'NetAmount',
        'Source',
        'ObjectSourceId',
        'UserId',
        'Status',
        'FiledBy',
        'ApprovedBy',
        'AuditedBy',
        'Notes',
        'CheckNo',
        'Bank',
        'CheckExpiration',
        'PaymentUsed'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BillNumber' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'DCRNumber' => 'string',
        'KwhUsed' => 'string',
        'Teller' => 'string',
        'OfficeTransacted' => 'string',
        'PostingDate' => 'date',
        'Surcharge' => 'string',
        'Form2307TwoPercent' => 'string',
        'Form2307FivePercent' => 'string',
        'AdditionalCharges' => 'string',
        'Deductions' => 'string',
        'NetAmount' => 'string',
        'Source' => 'string',
        'ObjectSourceId' => 'string',
        'UserId' => 'string',
        'Status' => 'string',
        'FiledBy' => 'string',
        'ApprovedBy' => 'string',
        'AuditedBy' => 'string',
        'Notes' => 'string',
        'CheckNo' => 'string',
        'Bank' => 'string',
        'CheckExpiration' => 'string',
        'PaymentUsed' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BillNumber' => 'nullable|string|max:255',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable|string|max:255',
        'ORNumber' => 'nullable|string|max:255',
        'ORDate' => 'nullable',
        'DCRNumber' => 'nullable|string|max:255',
        'KwhUsed' => 'nullable|string|max:255',
        'Teller' => 'nullable|string|max:255',
        'OfficeTransacted' => 'nullable|string|max:255',
        'PostingDate' => 'nullable',
        'PostingTime' => 'nullable',
        'Surcharge' => 'nullable|string|max:255',
        'Form2307TwoPercent' => 'nullable|string|max:255',
        'Form2307FivePercent' => 'nullable|string|max:255',
        'AdditionalCharges' => 'nullable|string|max:255',
        'Deductions' => 'nullable|string|max:255',
        'NetAmount' => 'nullable|string|max:255',
        'Source' => 'nullable|string|max:255',
        'ObjectSourceId' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string',
        'FiledBy' => 'nullable|string',
        'ApprovedBy' => 'nullable|string',
        'AuditedBy' => 'nullable|string',
        'Notes' => 'nullable|string',
        'CheckNo' => 'nullable|string',
        'Bank' => 'nullable|string',
        'CheckExpiration' => 'nullable|string',
        'PaymentUsed' => 'nullable|string',
    ];

    public static function roundDecimal($val, $precision) {
        $mult = pow(10, $precision); // Can be cached in lookup table        
        return floor($val * $mult) / $mult;
    }

}
