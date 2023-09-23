<?php

namespace App\Repositories;

use App\Models\ServiceConnectionAccountTypes;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionAccountTypesRepository
 * @package App\Repositories
 * @version July 26, 2021, 6:20 am UTC
*/

class ServiceConnectionAccountTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountType',
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
        return ServiceConnectionAccountTypes::class;
    }
}
