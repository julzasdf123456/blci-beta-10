<?php

namespace App\Repositories;

use App\Models\LocalWarehouseHead;
use App\Repositories\BaseRepository;

class LocalWarehouseHeadRepository extends BaseRepository
{
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
        'tot_amnt',
        'chkby',
        'appby',
        'stat',
        'rdate',
        'rtime',
        'walk_in',
        'appl_no',
        'address'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LocalWarehouseHead::class;
    }
}
