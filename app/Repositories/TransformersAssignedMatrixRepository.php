<?php

namespace App\Repositories;

use App\Models\TransformersAssignedMatrix;
use App\Repositories\BaseRepository;

/**
 * Class TransformersAssignedMatrixRepository
 * @package App\Repositories
 * @version September 21, 2021, 10:05 am PST
*/

class TransformersAssignedMatrixRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'MaterialsId',
        'Quantity'
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
        return TransformersAssignedMatrix::class;
    }
}
