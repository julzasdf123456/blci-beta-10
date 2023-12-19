<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoholWaterCollection extends Model
{
    public $table = 'BWUI_Collection';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'AccountNumber',
        'PreviousAccountNumber',
        'ConsumerName',
        'ORNumber',
        'ORDate',
        'AmountPaid',
        'Cash',
        'Particulars',
        'CheckNo',
        'CheckDate',
        'BankCode',
        'CheckAmount',
        'Collector',
    ];

    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'PreviousAccountNumber' => 'string',
        'ConsumerName' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'AmountPaid' => 'decimal:2',
        'Cash' => 'decimal:2',
        'Particulars' => 'string',
        'CheckNo' => 'string',
        'CheckDate' => 'date',
        'BankCode' => 'string',
        'CheckAmount' => 'decimal:2',
        'Collector' => 'string'
    ];

    public static array $rules = [
        'AccountNumber' => 'nullable|string|max:50',
        'PreviousAccountNumber' => 'nullable|string|max:50',
        'ConsumerName' => 'nullable|string|max:50',
        'ORNumber' => 'nullable|string|max:50',
        'ORDate' => 'nullable',
        'AmountPaid' => 'nullable|numeric',
        'Cash' => 'nullable|numeric',
        'Particulars' => 'nullable|string|max:500',
        'CheckNo' => 'nullable|string|max:50',
        'CheckDate' => 'nullable',
        'BankCode' => 'nullable|string|max:50',
        'CheckAmount' => 'nullable|numeric',
        'Collector' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
