<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TransacionPaymentDetails
 * @package App\Models
 * @version May 30, 2022, 11:05 am PST
 *
 * @property string $TransactionIndexId
 * @property string $Amount
 * @property string $PaymentUsed
 * @property string $Bank
 * @property string $CheckNo
 * @property string $CheckExpiration
 */
class TransacionPaymentDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_TransactionPaymentDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'TransactionIndexId',
        'Amount',
        'PaymentUsed',
        'Bank',
        'CheckNo',
        'CheckExpiration',
        'ORNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'TransactionIndexId' => 'string',
        'Amount' => 'string',
        'PaymentUsed' => 'string',
        'Bank' => 'string',
        'CheckNo' => 'string',
        'CheckExpiration' => 'string',
        'ORNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'TransactionIndexId' => 'nullable|string|max:255',
        'Amount' => 'nullable|string|max:255',
        'PaymentUsed' => 'nullable|string|max:255',
        'Bank' => 'nullable|string|max:255',
        'CheckNo' => 'nullable|string|max:255',
        'CheckExpiration' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ORNumber' => 'nullable|string'
    ];

    
}
