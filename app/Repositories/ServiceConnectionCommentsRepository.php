<?php

namespace App\Repositories;

use App\Models\ServiceConnectionComments;
use App\Repositories\BaseRepository;

class ServiceConnectionCommentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'ServiceConnectionId',
        'UserId',
        'Comments'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ServiceConnectionComments::class;
    }
}
