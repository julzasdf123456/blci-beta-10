<?php

namespace App\Repositories;

use App\Models\DistributionSystemLoss;
use App\Repositories\BaseRepository;

/**
 * Class DistributionSystemLossRepository
 * @package App\Repositories
 * @version April 16, 2022, 2:46 pm PST
*/

class DistributionSystemLossRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServicePeriod',
        'VictoriasSubstation',
        'SagaySubstation',
        'SanCarlosSubstation',
        'EscalanteSubstation',
        'LopezSubstation',
        'CadizSubstation',
        'IpiSubstation',
        'TobosoCalatravaSubstation',
        'VictoriasMillingCompany',
        'SanCarlosBionergy',
        'TotalEnergyInput',
        'EnergySales',
        'EnergyAdjustmentRecoveries',
        'TotalEnergyOutput',
        'TotalSystemLoss',
        'TotalSystemLossPercentage',
        'UserId',
        'From',
        'To',
        'Status',
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
        return DistributionSystemLoss::class;
    }
}
