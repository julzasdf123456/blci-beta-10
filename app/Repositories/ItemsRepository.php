<?php

namespace App\Repositories;

use App\Models\Items;
use App\Repositories\BaseRepository;

/**
 * Class ItemsRepository
 * @package App\Repositories
 * @version June 14, 2023, 8:02 am PST
*/

class ItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'itm_code',
        'itm_no',
        'itm_desc',
        'itm_specs',
        'itm_uom',
        'itm_aveqty',
        'itm_cat',
        'itm_yr',
        'itm_date',
        'itm_time',
        'itm_pcode'
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
        return Items::class;
    }
}
