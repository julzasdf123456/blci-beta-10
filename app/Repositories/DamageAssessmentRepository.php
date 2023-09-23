<?php

namespace App\Repositories;

use App\Models\DamageAssessment;
use App\Repositories\BaseRepository;

/**
 * Class DamageAssessmentRepository
 * @package App\Repositories
 * @version January 12, 2022, 8:13 am PST
*/

class DamageAssessmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Type',
        'ObjectName',
        'Feeder',
        'Town',
        'Status',
        'Notes',
        'DateFixed',
        'CrewAssigned',
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
        return DamageAssessment::class;
    }
}
