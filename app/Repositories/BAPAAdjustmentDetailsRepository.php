<?php

namespace App\Repositories;

use App\Models\BAPAAdjustmentDetails;
use App\Repositories\BaseRepository;

/**
 * Class BAPAAdjustmentDetailsRepository
 * @package App\Repositories
 * @version May 25, 2022, 11:05 am PST
*/

class BAPAAdjustmentDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'BillId',
        'DiscountPercentage',
        'DiscountAmount',
        'BAPAName',
        'ServicePeriod'
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
        return BAPAAdjustmentDetails::class;
    }
}
