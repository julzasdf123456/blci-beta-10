<?php

namespace App\Repositories;

use App\Models\CollectionDateAdjustments;
use App\Repositories\BaseRepository;

class CollectionDateAdjustmentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'UserId',
        'DateAssigned',
        'Notes',
        'Status',
        'AssignedBy'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return CollectionDateAdjustments::class;
    }
}
