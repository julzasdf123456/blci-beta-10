<?php

namespace App\Repositories;

use App\Models\SystemSettings;
use App\Repositories\BaseRepository;

class SystemSettingsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'PasswordDaysExpire'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SystemSettings::class;
    }
}
