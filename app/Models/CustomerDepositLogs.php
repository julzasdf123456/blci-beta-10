<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDepositLogs extends Model
{
    public $table = 'Billing_CustomerDepositLogs';

    protected $connection = 'sqlsrv';
    
    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'LogDetails',
        'UserId'
    ];

    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'LogDetails' => 'string',
        'UserId' => 'string'
    ];

    public static array $rules = [
        'AccountNumber' => 'nullable|string|max:50',
        'LogDetails' => 'nullable|string|max:1000',
        'UserId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
