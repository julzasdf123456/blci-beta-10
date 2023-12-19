<?php

namespace App\Repositories;

use App\Models\BoholWaterServiceAccounts;
use App\Repositories\BaseRepository;

class BoholWaterServiceAccountsRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        'OtherCharges'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BoholWaterServiceAccounts::class;
    }
}
