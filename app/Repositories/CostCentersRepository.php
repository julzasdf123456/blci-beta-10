<?php

namespace App\Repositories;

use App\Models\CostCenters;
use App\Repositories\BaseRepository;

/**
 * Class CostCentersRepository
 * @package App\Repositories
 * @version June 27, 2023, 9:26 am PST
*/

class CostCentersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'CostCode',
        'CostName',
        'CostDepartment'
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
        return CostCenters::class;
    }
}
