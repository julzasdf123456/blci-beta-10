<?php

namespace App\Repositories;

use App\Models\StructureAssignments;
use App\Repositories\BaseRepository;

/**
 * Class StructureAssignmentsRepository
 * @package App\Repositories
 * @version September 17, 2021, 9:58 am PST
*/

class StructureAssignmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'StructureId'
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
        return StructureAssignments::class;
    }
}
