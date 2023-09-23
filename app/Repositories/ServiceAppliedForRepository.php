<?php

namespace App\Repositories;

use App\Models\ServiceAppliedFor;
use App\Repositories\BaseRepository;

/**
 * Class ServiceAppliedForRepository
 * @package App\Repositories
 * @version May 8, 2023, 10:03 am PST
*/

class ServiceAppliedForRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceAppliedFor',
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
        return ServiceAppliedFor::class;
    }
}
