<?php

namespace App\Repositories;

use App\Models\PaidBills;
use App\Repositories\BaseRepository;

/**
 * Class PaidBillsRepository
 * @package App\Repositories
 * @version February 11, 2022, 8:16 am PST
*/

class PaidBillsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BillNumber',
        'AccountNumber',
        'ServicePeriod',
        'ORNumber',
        'ORDate',
        'DCRNumber',
        'KwhUsed',
        'Teller',
        'OfficeTransacted',
        'PostingDate',
        'PostingTime',
        'Surcharge',
        'Form2307TwoPercent',
        'Form2307FivePercent',
        'AdditionalCharges',
        'Deductions',
        'NetAmount',
        'Source',
        'ObjectSourceId',
        'UserId'
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
        return PaidBills::class;
    }
}
