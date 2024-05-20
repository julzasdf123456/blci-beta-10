<?php

namespace App\Repositories;

use App\Models\LineAndMeteringServices;
use App\Repositories\BaseRepository;

class LineAndMeteringServicesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'TypeOfService',
        'MeterSealNumber',
        'IsLeadSeal',
        'MeterStatus',
        'MeterNumber',
        'Multiplier',
        'MeterType',
        'MeterBrand',
        'Notes',
        'ServiceDate',
        'UserId',
        'PrivateElectrician',
        'LineLength',
        'ConductorType',
        'ConductorSize',
        'ConductorUnit',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LineAndMeteringServices::class;
    }
}
