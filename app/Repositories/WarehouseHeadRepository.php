<?php

namespace App\Repositories;

use App\Models\WarehouseHead;
use App\Repositories\BaseRepository;

/**
 * Class WarehouseHeadRepository
 * @package App\Repositories
 * @version June 13, 2023, 4:05 pm PST
*/

class WarehouseHeadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'orderno',
        'ent_no',
        'misno',
        'tdate',
        'emp_id',
        'ccode',
        'dept',
        'pcode',
        'reqby',
        'invoice',
        'orno',
        'purpose',
        'serv_code',
        'account_no',
        'cust_name',
        'tot_amt',
        'chkby',
        'appby',
        'stat',
        'rdate',
        'rtime',
        'walk_in',
        'appl_no'
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
        return WarehouseHead::class;
    }
}
