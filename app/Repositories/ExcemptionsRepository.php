<?php

namespace App\Repositories;

use App\Models\Excemptions;
use App\Repositories\BaseRepository;

/**
 * Class ExcemptionsRepository
 * @package App\Repositories
 * @version July 30, 2022, 9:49 am PST
*/

class ExcemptionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
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
        return Excemptions::class;
    }
}
