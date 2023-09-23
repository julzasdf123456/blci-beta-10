<?php

namespace App\Repositories;

use App\Models\Structures;
use App\Repositories\BaseRepository;

/**
 * Class StructuresRepository
 * @package App\Repositories
 * @version September 16, 2021, 8:28 am PST
*/

class StructuresRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Type',
        'Data'
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
        return Structures::class;
    }
}
