<?php

namespace App\Repositories;

use App\Models\MaterialsMatrix;
use App\Repositories\BaseRepository;

/**
 * Class MaterialsMatrixRepository
 * @package App\Repositories
 * @version September 16, 2021, 8:29 am PST
*/

class MaterialsMatrixRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return MaterialsMatrix::class;
    }
}
