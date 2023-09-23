<?php

namespace App\Repositories;

use App\Models\KwhSales;
use App\Repositories\BaseRepository;

/**
 * Class KwhSalesRepository
 * @package App\Repositories
 * @version March 28, 2022, 3:31 pm PST
*/

class KwhSalesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServicePeriod',
        'Town',
        'BilledKwh',
        'ConsumedKwh',
        'NoOfConsumers',
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
        return KwhSales::class;
    }
}
