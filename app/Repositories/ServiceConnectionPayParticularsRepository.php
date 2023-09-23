<?php

namespace App\Repositories;

use App\Models\ServiceConnectionPayParticulars;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionPayParticularsRepository
 * @package App\Repositories
 * @version August 17, 2021, 12:43 am UTC
*/

class ServiceConnectionPayParticularsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Particular',
        'Description',
        'VatPercentage',
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
        return ServiceConnectionPayParticulars::class;
    }
}
