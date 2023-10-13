<?php

namespace App\Repositories;

use App\Models\CustomerDepositInterests;
use App\Repositories\BaseRepository;

class CustomerDepositInterestsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'AccountNumber',
        'InterestEarned',
        'CurrentAmountRemaining',
        'OriginalAmount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return CustomerDepositInterests::class;
    }
}
