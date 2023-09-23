<?php

namespace App\Repositories;

use App\Models\BillOfMaterialsIndex;
use App\Repositories\BaseRepository;

/**
 * Class BillOfMaterialsIndexRepository
 * @package App\Repositories
 * @version September 16, 2021, 8:30 am PST
*/

class BillOfMaterialsIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Date',
        'SubTotal',
        'LaborCost',
        'Others',
        'Total'
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
        return BillOfMaterialsIndex::class;
    }
}
