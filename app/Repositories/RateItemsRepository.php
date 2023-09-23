<?php

namespace App\Repositories;

use App\Models\RateItems;
use App\Repositories\BaseRepository;

/**
 * Class RateItemsRepository
 * @package App\Repositories
 * @version April 17, 2022, 12:44 pm PST
*/

class RateItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'RateItem',
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
        return RateItems::class;
    }
}
