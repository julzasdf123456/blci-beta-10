<?php

namespace App\Repositories;

use App\Models\ChangeMeterLogs;
use App\Repositories\BaseRepository;

/**
 * Class ChangeMeterLogsRepository
 * @package App\Repositories
 * @version April 18, 2022, 5:07 pm PST
*/

class ChangeMeterLogsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'OldMeterSerial',
        'NewMeterSerial',
        'PullOutReading',
        'AdditionalKwhForNextBilling',
        'Status'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ChangeMeterLogs::class;
    }
}
