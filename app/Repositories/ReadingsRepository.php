<?php

namespace App\Repositories;

use App\Models\Readings;
use App\Repositories\BaseRepository;

/**
 * Class ReadingsRepository
 * @package App\Repositories
 * @version January 25, 2022, 11:10 am PST
*/

class ReadingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'ReadingTimestamp',
        'KwhUsed',
        'DemandKwhUsed',
        'Notes',
        'Latitude',
        'Longitude'
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
        return Readings::class;
    }
}
