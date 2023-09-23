<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ArrearsLedgerDistribution
 * @package App\Models
 * @version February 8, 2022, 4:13 pm PST
 *
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $Amount
 * @property string $IsBilled
 * @property string $IsPaid
 * @property string $LinkedBillNumber
 * @property string $Notes
 */
class ArrearsLedgerDistribution extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_ArrearsLedgerDistribution';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ServicePeriod',
        'Amount',
        'IsBilled',
        'IsPaid',
        'LinkedBillNumber',
        'Notes'
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
        'Amount' => 'string',
        'IsBilled' => 'string',
        'IsPaid' => 'string',
        'LinkedBillNumber' => 'string',
        'Notes' => 'string'
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
        'Amount' => 'nullable|string|max:255',
        'IsBilled' => 'nullable|string|max:255',
        'IsPaid' => 'nullable|string|max:255',
        'LinkedBillNumber' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
