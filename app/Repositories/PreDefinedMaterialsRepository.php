<?php

namespace App\Repositories;

use App\Models\PreDefinedMaterials;
use App\Repositories\BaseRepository;

/**
 * Class PreDefinedMaterialsRepository
 * @package App\Repositories
 * @version October 4, 2021, 9:07 am PST
*/

class PreDefinedMaterialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'NEACode',
        'Quantity',
        'Options',
        'ApplicationType',
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
        return PreDefinedMaterials::class;
    }
}
