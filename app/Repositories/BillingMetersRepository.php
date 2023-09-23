<?php

namespace App\Repositories;

use App\Models\BillingMeters;
use App\Repositories\BaseRepository;

/**
 * Class BillingMetersRepository
 * @package App\Repositories
 * @version November 22, 2021, 11:39 am PST
*/

class BillingMetersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceAccountId',
        'SerialNumber',
        'SealNumber',
        'Brand',
        'Model',
        'Multiplier',
        'Status',
        'ConnectionDate',
        'LatestReadingDate',
        'DateDisconnected',
        'DateTransfered'
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
        return BillingMeters::class;
    }
}
