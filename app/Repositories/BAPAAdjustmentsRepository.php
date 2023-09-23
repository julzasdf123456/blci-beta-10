<?php

namespace App\Repositories;

use App\Models\BAPAAdjustments;
use App\Repositories\BaseRepository;

/**
 * Class BAPAAdjustmentsRepository
 * @package App\Repositories
 * @version May 25, 2022, 10:58 am PST
*/

class BAPAAdjustmentsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BAPAName',
        'ServicePeriod',
        'DiscountPercentage',
        'DiscountAmount',
        'NumberOfConsumers',
        'SubTotal',
        'NetAmount',
        'UserId',
        'Route'
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
        return BAPAAdjustments::class;
    }
}
