<?php

namespace App\Repositories;

use App\Models\ServiceConnectionMatPayables;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionMatPayablesRepository
 * @package App\Repositories
 * @version August 17, 2021, 12:43 am UTC
*/

class ServiceConnectionMatPayablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Material',
        'Rate',
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
        return ServiceConnectionMatPayables::class;
    }
}
