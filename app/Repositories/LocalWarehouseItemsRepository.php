<?php

namespace App\Repositories;

use App\Models\LocalWarehouseItems;
use App\Repositories\BaseRepository;

class LocalWarehouseItemsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'reqno',
        'ent_no',
        'tdate',
        'itemcd',
        'ascode',
        'qty',
        'uom',
        'cst',
        'amnt',
        'itemno',
        'rdate',
        'rtime',
        'salesprice'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LocalWarehouseItems::class;
    }
}
