<?php

namespace App\Repositories;

use App\Models\SpanningIndex;
use App\Repositories\BaseRepository;

/**
 * Class SpanningIndexRepository
 * @package App\Repositories
 * @version September 24, 2021, 2:41 pm PST
*/

class SpanningIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'NeaCode',
        'Structure',
        'Description',
        'Size',
        'Type',
        'SpliceNeaCode'
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
        return SpanningIndex::class;
    }
}
