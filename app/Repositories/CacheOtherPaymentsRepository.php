<?php

namespace App\Repositories;

use App\Models\CacheOtherPayments;
use App\Repositories\BaseRepository;

/**
 * Class CacheOtherPaymentsRepository
 * @package App\Repositories
 * @version February 28, 2022, 8:20 am PST
*/

class CacheOtherPaymentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'TransactionIndexId',
        'Particular',
        'Amount',
        'VAT',
        'Total',
        'AccountCode'
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
        return CacheOtherPayments::class;
    }
}
