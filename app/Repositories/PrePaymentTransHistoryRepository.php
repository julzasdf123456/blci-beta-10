<?php

namespace App\Repositories;

use App\Models\PrePaymentTransHistory;
use App\Repositories\BaseRepository;

/**
 * Class PrePaymentTransHistoryRepository
 * @package App\Repositories
 * @version March 30, 2022, 10:28 am PST
*/

class PrePaymentTransHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'Method',
        'Amount',
        'UserId',
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
        return PrePaymentTransHistory::class;
    }
}
