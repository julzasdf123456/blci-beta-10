<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDepositInterests extends Model
{
    public $table = 'Billing_CustomerDepositInterests';

    protected $connection = 'sqlsrv';
    
    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'InterestEarned',
        'CurrentAmountRemaining',
        'OriginalAmount'
    ];

    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'InterestEarned' => 'decimal:2',
        'CurrentAmountRemaining' => 'decimal:2',
        'OriginalAmount' => 'decimal:2'
    ];

    public static array $rules = [
        'AccountNumber' => 'nullable|string|max:50',
        'InterestEarned' => 'nullable|numeric',
        'CurrentAmountRemaining' => 'nullable|numeric',
        'OriginalAmount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
