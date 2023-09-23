<?php

namespace App\Repositories;

use App\Models\ServiceConnectionChecklistsRep;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionChecklistsRepRepository
 * @package App\Repositories
 * @version August 24, 2021, 1:30 pm PST
*/

class ServiceConnectionChecklistsRepRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Checklist',
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
        return ServiceConnectionChecklistsRep::class;
    }
}
