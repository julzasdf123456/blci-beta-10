<?php

namespace App\Repositories;

use App\Models\PendingBillAdjustments;
use App\Repositories\BaseRepository;

/**
 * Class PendingBillAdjustmentsRepository
 * @package App\Repositories
 * @version March 22, 2022, 1:31 pm PST
*/

class PendingBillAdjustmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ReadingId',
        'KwhUsed',
        'AccountNumber',
        'ServicePeriod',
        'Confirmed',
        'ReadDate'
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
        return PendingBillAdjustments::class;
    }
}
