<?php

namespace App\Repositories;

use App\Models\ServiceConnectionChecklists;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionChecklistsRepository
 * @package App\Repositories
 * @version August 24, 2021, 1:31 pm PST
*/

class ServiceConnectionChecklistsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'ChecklistId',
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
        return ServiceConnectionChecklists::class;
    }
}
