<?php

namespace App\Repositories;

use App\Models\BillingTransformers;
use App\Repositories\BaseRepository;

/**
 * Class BillingTransformersRepository
 * @package App\Repositories
 * @version November 22, 2021, 11:38 am PST
*/

class BillingTransformersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceAccountId',
        'TransformerNumber',
        'Rating',
        'RentalFee',
        'Load'
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
        return BillingTransformers::class;
    }
}
