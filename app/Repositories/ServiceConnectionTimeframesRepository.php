<?php

namespace App\Repositories;

use App\Models\ServiceConnectionTimeframes;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionTimeframesRepository
 * @package App\Repositories
 * @version August 19, 2021, 6:35 am UTC
*/

class ServiceConnectionTimeframesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'UserId',
        'Status',
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
        return ServiceConnectionTimeframes::class;
    }
}
