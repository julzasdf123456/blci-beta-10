<?php

namespace App\Repositories;

use App\Models\SmsSettings;
use App\Repositories\BaseRepository;

class SmsSettingsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'Bills',
        'NoticeOfDisconnection',
        'ServiceConnectionReception',
        'InspectionCreation',
        'PaymentApproved',
        'InspectionToday'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SmsSettings::class;
    }
}
