<?php

namespace App\Repositories;

use App\Models\ReadingFromText;
use App\Repositories\BaseRepository;

class ReadingFromTextRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'HouseNumber',
        'ConsumerName',
        'OldAccountNo',
        'NewMeterNumber',
        'ReadingMonth',
        'ServicePeriod',
        'LastReading',
        'OldMeterNumber',
        'MeterReader',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ReadingFromText::class;
    }
}
