<?php

namespace App\Repositories;

use App\Models\ReadingSchedules;
use App\Repositories\BaseRepository;

/**
 * Class ReadingSchedulesRepository
 * @package App\Repositories
 * @version January 17, 2022, 9:45 am PST
*/

class ReadingSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AreaCode',
        'GroupCode',
        'ServicePeriod',
        'ScheduledDate',
        'MeterReader'
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
        return ReadingSchedules::class;
    }
}
