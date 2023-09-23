<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PrePaymentTransHistory
 * @package App\Models
 * @version March 30, 2022, 10:28 am PST
 *
 * @property string $AccountNumber
 * @property string $Method
 * @property string $Amount
 * @property string $UserId
 * @property string $Notes
 */
class PrePaymentTransHistory extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_PrePaymentTransactionHistory';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'Method',
        'Amount',
        'UserId',
        'Notes',
        'ORNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'Method' => 'string',
        'Amount' => 'string',
        'UserId' => 'string',
        'Notes' => 'string',
        'ORNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'Method' => 'nullable|string|max:255',
        'Amount' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ORNumber' => 'nullable|string'
    ];

    
}
