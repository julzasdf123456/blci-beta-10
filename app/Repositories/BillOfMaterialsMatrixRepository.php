<?php

namespace App\Repositories;

use App\Models\BillOfMaterialsMatrix;
use App\Repositories\BaseRepository;

/**
 * Class BillOfMaterialsMatrixRepository
 * @package App\Repositories
 * @version September 17, 2021, 3:39 pm PST
*/

class BillOfMaterialsMatrixRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'StructureAssigningId',
        'StructureId',
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
        return BillOfMaterialsMatrix::class;
    }
}
