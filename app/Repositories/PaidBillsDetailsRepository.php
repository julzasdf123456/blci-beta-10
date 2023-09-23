<?php

namespace App\Repositories;

use App\Models\PaidBillsDetails;
use App\Repositories\BaseRepository;

/**
 * Class PaidBillsDetailsRepository
 * @package App\Repositories
 * @version May 27, 2022, 11:14 am PST
*/

class PaidBillsDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'BillId',
        'ORNumber',
        'Amount',
        'PaymentUsed',
        'CheckNo',
        'Bank',
        'CheckExpiration',
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
        return PaidBillsDetails::class;
    }
}
