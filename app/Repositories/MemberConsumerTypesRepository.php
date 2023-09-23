<?php

namespace App\Repositories;

use App\Models\MemberConsumerTypes;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumerTypesRepository
 * @package App\Repositories
 * @version July 16, 2021, 2:32 am UTC
*/

class MemberConsumerTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Type',
        'Description'
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
        return MemberConsumerTypes::class;
    }
}
