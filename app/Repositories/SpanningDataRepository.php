<?php

namespace App\Repositories;

use App\Models\SpanningData;
use App\Repositories\BaseRepository;

/**
 * Class SpanningDataRepository
 * @package App\Repositories
 * @version September 27, 2021, 8:20 am PST
*/

class SpanningDataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'PrimarySpan',
        'PrimarySize',
        'PrimaryType',
        'NeutralSpan',
        'NeutralSize',
        'NeutralType',
        'SecondarySpan',
        'SecondarySize',
        'SecondaryType',
        'SDWSpan',
        'SDWSize',
        'SDWType'
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
        return SpanningData::class;
    }
}
