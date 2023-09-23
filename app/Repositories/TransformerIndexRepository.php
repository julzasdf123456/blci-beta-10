<?php

namespace App\Repositories;

use App\Models\TransformerIndex;
use App\Repositories\BaseRepository;

/**
 * Class TransformerIndexRepository
 * @package App\Repositories
 * @version September 21, 2021, 9:21 am PST
*/

class TransformerIndexRepository extends BaseRepository
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
        return TransformerIndex::class;
    }
}
