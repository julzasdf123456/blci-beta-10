<?php

namespace App\Repositories;

use App\Models\ServiceConnectionMatPayments;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionMatPaymentsRepository
 * @package App\Repositories
 * @version August 17, 2021, 1:14 am UTC
*/

class ServiceConnectionMatPaymentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Material',
        'Quantity',
        'Vat',
        'Total'
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
        return ServiceConnectionMatPayments::class;
    }
}
