<?php

namespace App\Repositories;

use App\Models\BillsOfMaterialsSummary;
use App\Repositories\BaseRepository;

/**
 * Class BillsOfMaterialsSummaryRepository
 * @package App\Repositories
 * @version September 23, 2021, 1:46 pm PST
*/

class BillsOfMaterialsSummaryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'ExcludeTransformerLaborCost',
        'TransformerChangedPrice',
        'MonthDuration',
        'TransformerLaborCostPercentage',
        'MaterialLaborCostPercentage',
        'HandlingCostPercentage',
        'SubTotal',
        'LaborCost',
        'HandlingCost',
        'Total',
        'TotalVAT'
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
        return BillsOfMaterialsSummary::class;
    }
}
