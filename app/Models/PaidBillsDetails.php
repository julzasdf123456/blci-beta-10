<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaidBillsDetails
 * @package App\Models
 * @version May 27, 2022, 11:14 am PST
 *
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $BillId
 * @property string $ORNumber
 * @property string $Amount
 * @property string $PaymentUsed
 * @property string $CheckNo
 * @property string $Bank
 * @property string $CheckExpiration
 * @property string $UserId
 */
class PaidBillsDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_PaidBillsDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ServicePeriod',
        'BillId',
        'ORNumber',
        'Amount',
        'PaymentUsed',
        'CheckNo',
        'Bank',
        'CheckExpiration',
        'UserId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'date',
        'BillId' => 'string',
        'ORNumber' => 'string',
        'Amount' => 'string',
        'PaymentUsed' => 'string',
        'CheckNo' => 'string',
        'Bank' => 'string',
        'CheckExpiration' => 'date',
        'UserId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'BillId' => 'nullable|string|max:255',
        'ORNumber' => 'nullable|string|max:255',
        'Amount' => 'nullable|string|max:255',
        'PaymentUsed' => 'nullable|string|max:255',
        'CheckNo' => 'nullable|string|max:255',
        'Bank' => 'nullable|string|max:255',
        'CheckExpiration' => 'nullable',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
