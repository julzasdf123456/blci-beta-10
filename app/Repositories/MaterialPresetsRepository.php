<?php

namespace App\Repositories;

use App\Models\MaterialPresets;
use App\Repositories\BaseRepository;

class MaterialPresetsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'MeterBaseSocket',
        'MetalboxTypeA',
        'MetalboxTypeB',
        'Pipe',
        'EntranceCap',
        'Adapter',
        'Locknot',
        'Mailbox',
        'Buckle',
        'PvcElbow',
        'StainlessStrap',
        'Plyboard',
        'StrainInsulator',
        'StraindedWireEight',
        'StrandedWireSix',
        'TwistedWireSix',
        'TwistedWireFour'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MaterialPresets::class;
    }
}
