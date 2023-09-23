<?php

namespace App\Repositories;

use App\Models\ServiceConnectionTotalPayments;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionTotalPaymentsRepository
 * @package App\Repositories
 * @version August 19, 2021, 5:53 am UTC
*/

class ServiceConnectionTotalPaymentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'SubTotal',
        'Form2307TwoPercent',
        'Form2307FivePercent',
        'TotalVat',
        'Total',
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
        return ServiceConnectionTotalPayments::class;
    }
}
