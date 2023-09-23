<?php

namespace App\Repositories;

use App\Models\ServiceConnectionLgLoadInsp;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionLgLoadInspRepository
 * @package App\Repositories
 * @version September 15, 2021, 4:41 pm PST
*/

class ServiceConnectionLgLoadInspRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Assessment',
        'DateOfInspection',
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
        return ServiceConnectionLgLoadInsp::class;
    }
}
