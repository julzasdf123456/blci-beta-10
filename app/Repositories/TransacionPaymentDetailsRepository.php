<?php

namespace App\Repositories;

use App\Models\TransacionPaymentDetails;
use App\Repositories\BaseRepository;

/**
 * Class TransacionPaymentDetailsRepository
 * @package App\Repositories
 * @version May 30, 2022, 11:05 am PST
*/

class TransacionPaymentDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TransactionIndexId',
        'Amount',
        'PaymentUsed',
        'Bank',
        'CheckNo',
        'CheckExpiration'
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
        return TransacionPaymentDetails::class;
    }
}
