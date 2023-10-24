<?php

namespace App\Repositories;

use App\Models\CustomerDepositLogs;
use App\Repositories\BaseRepository;

class CustomerDepositLogsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'AccountNumber',
        'LogDetails',
        'UserId'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return CustomerDepositLogs::class;
    }
}
