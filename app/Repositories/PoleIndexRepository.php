<?php

namespace App\Repositories;

use App\Models\PoleIndex;
use App\Repositories\BaseRepository;

/**
 * Class PoleIndexRepository
 * @package App\Repositories
 * @version September 22, 2021, 11:55 am PST
*/

class PoleIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'NEACode',
        'Type'
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
        return PoleIndex::class;
    }
}
