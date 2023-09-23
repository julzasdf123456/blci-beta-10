<?php

namespace App\Repositories;

use App\Models\PrePaymentBalance;
use App\Repositories\BaseRepository;

/**
 * Class PrePaymentBalanceRepository
 * @package App\Repositories
 * @version March 29, 2022, 9:32 pm PST
*/

class PrePaymentBalanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'Balance',
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
        return PrePaymentBalance::class;
    }
}
