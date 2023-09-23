<?php

namespace App\Repositories;

use App\Models\PreDefinedMaterialsMatrix;
use App\Repositories\BaseRepository;

/**
 * Class PreDefinedMaterialsMatrixRepository
 * @package App\Repositories
 * @version October 5, 2021, 9:37 am PST
*/

class PreDefinedMaterialsMatrixRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'NEACode',
        'Description',
        'Quantity',
        'Options',
        'ApplicationType',
        'Cost',
        'LaborCost',
        'Notes',
        'LaborPercentage'
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
        return PreDefinedMaterialsMatrix::class;
    }
}
