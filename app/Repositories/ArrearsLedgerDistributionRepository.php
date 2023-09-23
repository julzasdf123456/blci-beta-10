<?php

namespace App\Repositories;

use App\Models\ArrearsLedgerDistribution;
use App\Repositories\BaseRepository;

/**
 * Class ArrearsLedgerDistributionRepository
 * @package App\Repositories
 * @version February 8, 2022, 4:13 pm PST
*/

class ArrearsLedgerDistributionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'Amount',
        'IsBilled',
        'IsPaid',
        'LinkedBillNumber',
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
        return ArrearsLedgerDistribution::class;
    }
}
