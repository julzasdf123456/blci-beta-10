<?php

namespace App\Repositories;

use App\Models\ServiceConnectionCrew;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionCrewRepository
 * @package App\Repositories
 * @version September 8, 2021, 8:25 am PST
*/

class ServiceConnectionCrewRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'StationName',
        'CrewLeader',
        'Members',
        'Notes'
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
        return ServiceConnectionCrew::class;
    }
}
