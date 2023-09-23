<?php

namespace App\Repositories;

use App\Models\MaterialAssets;
use App\Repositories\BaseRepository;

/**
 * Class MaterialAssetsRepository
 * @package App\Repositories
 * @version September 16, 2021, 8:29 am PST
*/

class MaterialAssetsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Description',
        'Amount'
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
        return MaterialAssets::class;
    }
}
