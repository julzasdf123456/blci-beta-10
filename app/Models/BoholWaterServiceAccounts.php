<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoholWaterServiceAccounts extends Model
{
    public $table = 'BWUI_ServiceAccounts';

    protected $connection = 'sqlsrv';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'PreviousAccountNumber',
        'ConsumerName',
        'ConnectionType',
        'MeterNumber',
        'TotalBill',
        'WaterBill',
        'BillsPenalty',
        'SalesCharge',
        'SalesPenalty',
        'OtherCharges',
    ];

    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'PreviousAccountNumber' => 'string',
        'ConsumerName' => 'string',
        'ConnectionType' => 'string',
        'MeterNumber' => 'string',
        'TotalBill' => 'decimal:2',
        'WaterBill' => 'decimal:2',
        'BillsPenalty' => 'decimal:2',
        'SalesCharge' => 'decimal:2',
        'SalesPenalty' => 'decimal:2',
        'OtherCharges' => 'decimal:2'
    ];

    public static array $rules = [
        'AccountNumber' => 'nullable|string|max:50',
        'PreviousAccountNumber' => 'nullable|string|max:50',
        'ConsumerName' => 'nullable|string|max:500',
        'ConnectionType' => 'nullable|string|max:50',
        'MeterNumber' => 'nullable|string|max:50',
        'TotalBill' => 'nullable|numeric',
        'WaterBill' => 'nullable|numeric',
        'BillsPenalty' => 'nullable|numeric',
        'SalesCharge' => 'nullable|numeric',
        'SalesPenalty' => 'nullable|numeric',
        'OtherCharges' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
