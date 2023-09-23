<?php

namespace App\Repositories;

use App\Models\BillMiscellaneous;
use App\Repositories\BaseRepository;

class BillMiscellaneousRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Name',
        'Description',
        'Balance',
        'Operation',
        'Status',
        'Terms',
        'Notes',
        'EndDate'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BillMiscellaneous::class;
    }
}
