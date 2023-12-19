<?php

namespace App\Repositories;

use App\Models\BoholWaterCollection;
use App\Repositories\BaseRepository;

class BoholWaterCollectionRepository extends BaseRepository
{
    protected $fieldSearchable = [
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
        'Collector'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BoholWaterCollection::class;
    }
}
