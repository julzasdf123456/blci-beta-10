<?php

namespace App\Repositories;

use App\Models\TransactionDetails;
use App\Repositories\BaseRepository;

/**
 * Class TransactionDetailsRepository
 * @package App\Repositories
 * @version February 10, 2022, 9:11 am PST
*/

class TransactionDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TransactionIndexId',
        'Particular',
        'Amount',
        'VAT',
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
        return TransactionDetails::class;
    }
}
