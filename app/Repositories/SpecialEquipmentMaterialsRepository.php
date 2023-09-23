<?php

namespace App\Repositories;

use App\Models\SpecialEquipmentMaterials;
use App\Repositories\BaseRepository;

/**
 * Class SpecialEquipmentMaterialsRepository
 * @package App\Repositories
 * @version November 16, 2021, 9:44 am PST
*/

class SpecialEquipmentMaterialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'NEACode'
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
        return SpecialEquipmentMaterials::class;
    }
}
