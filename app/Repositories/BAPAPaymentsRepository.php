<?php

namespace App\Repositories;

use App\Models\BAPAPayments;
use App\Repositories\BaseRepository;

/**
 * Class BAPAPaymentsRepository
 * @package App\Repositories
 * @version April 11, 2022, 3:30 pm PST
*/

class BAPAPaymentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BAPAName',
        'ServicePeriod',
        'ORNumber',
        'ORDate',
        'SubTotal',
        'TwoPercentDiscount',
        'FivePercentDiscount',
        'AdditionalCharges',
        'Deductions',
        'VAT',
        'Total',
        'Teller',
        'NoOfConsumersPaid',
        'Status',
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
        return BAPAPayments::class;
    }
}
