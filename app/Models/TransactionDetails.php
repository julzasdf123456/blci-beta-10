<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TransactionDetails
 * @package App\Models
 * @version February 10, 2022, 9:11 am PST
 *
 * @property string $TransactionIndexId
 * @property string $Particular
 * @property string $Amount
 * @property string $VAT
 * @property string $Total
 */
class TransactionDetails extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_TransactionDetails';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'TransactionIndexId',
        'Particular',
        'Amount',
        'VAT',
        'Total',
        'AccountCode'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'TransactionIndexId' => 'string',
        'Particular' => 'string',
        'Amount' => 'string',
        'VAT' => 'string',
        'Total' => 'string',
        'AccountCode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'TransactionIndexId' => 'nullable|string|max:255',
        'Particular' => 'nullable|string|max:350',
        'Amount' => 'nullable|string|max:255',
        'VAT' => 'nullable|string|max:255',
        'Total' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AccountCode' => 'nullable|string'
    ];

    
}
