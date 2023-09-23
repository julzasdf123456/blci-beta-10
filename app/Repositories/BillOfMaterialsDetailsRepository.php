<?php

namespace App\Repositories;

use App\Models\BillOfMaterialsDetails;
use App\Repositories\BaseRepository;

/**
 * Class BillOfMaterialsDetailsRepository
 * @package App\Repositories
 * @version September 16, 2021, 8:30 am PST
*/

class BillOfMaterialsDetailsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BillOfMaterialsId',
        'NeaCode',
        'Description',
        'Rate',
        'Quantity',
        'Amount'
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
        return BillOfMaterialsDetails::class;
    }
}
