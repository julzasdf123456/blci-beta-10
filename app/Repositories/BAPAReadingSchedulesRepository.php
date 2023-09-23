<?php

namespace App\Repositories;

use App\Models\BAPAReadingSchedules;
use App\Repositories\BaseRepository;

/**
 * Class BAPAReadingSchedulesRepository
 * @package App\Repositories
 * @version April 7, 2022, 4:37 pm PST
*/

class BAPAReadingSchedulesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServicePeriod',
        'Town',
        'BAPAName',
        'Status',
        'DownloadedBy'
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
        return BAPAReadingSchedules::class;
    }
}
