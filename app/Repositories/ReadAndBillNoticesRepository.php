<?php

namespace App\Repositories;

use App\Models\ReadAndBillNotices;
use App\Repositories\BaseRepository;

class ReadAndBillNoticesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Notes',
        'ServicePeriod',
        'UserId',
        'Zone',
        'Block'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ReadAndBillNotices::class;
    }
}
