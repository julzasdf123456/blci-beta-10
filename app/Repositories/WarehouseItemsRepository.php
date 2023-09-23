<?php

namespace App\Repositories;

use App\Models\WarehouseItems;
use App\Repositories\BaseRepository;

/**
 * Class WarehouseItemsRepository
 * @package App\Repositories
 * @version June 13, 2023, 4:09 pm PST
*/

class WarehouseItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'reqno',
        'ent_no',
        'tdate',
        'itemcd',
        'ascode',
        'qty',
        'uom',
        'cst',
        'amt',
        'itemno',
        'rdate',
        'rtime'
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
        return WarehouseItems::class;
    }
}
