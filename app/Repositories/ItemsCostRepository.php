<?php

namespace App\Repositories;

use App\Models\ItemsCost;
use App\Repositories\BaseRepository;

/**
 * Class ItemsCostRepository
 * @package App\Repositories
 * @version June 1, 2023, 2:29 pm PST
*/

class ItemsCostRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cst_no',
        'rrno',
        'it_code',
        'ave_qty',
        'qty',
        'uom',
        'cst',
        'amt',
        'rdate',
        'rtime',
        'categ',
        'specs'
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
        return ItemsCost::class;
    }
}
