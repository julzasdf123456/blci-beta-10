<?php

namespace App\Repositories;

use App\Models\DCRSummaryTransactions;
use App\Repositories\BaseRepository;

/**
 * Class DCRSummaryTransactionsRepository
 * @package App\Repositories
 * @version April 25, 2022, 9:33 am PST
*/

class DCRSummaryTransactionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'GLCode',
        'NEACode',
        'Description',
        'Amount',
        'Day',
        'Time',
        'Teller',
        'DCRNumber',
        'Status'
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
        return DCRSummaryTransactions::class;
    }
}
