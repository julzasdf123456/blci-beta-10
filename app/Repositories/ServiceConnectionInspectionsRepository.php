<?php

namespace App\Repositories;

use App\Models\ServiceConnectionInspections;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionInspectionsRepository
 * @package App\Repositories
 * @version July 26, 2021, 7:43 am UTC
*/

class ServiceConnectionInspectionsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'SEMainCircuitBreakerAsPlan',
        'SEMainCircuitBreakerAsInstalled',
        'SENoOfBranchesAsPlan',
        'SENoOfBranchesAsInstalled',
        'PoleGIEstimatedDiameter',
        'PoleGIHeight',
        'PoleGINoOfLiftPoles',
        'PoleConcreteEstimatedDiameter',
        'PoleConcreteHeight',
        'PoleConcreteNoOfLiftPoles',
        'PoleHardwoodEstimatedDiameter',
        'PoleHardwoodHeight',
        'PoleHardwoodNoOfLiftPoles',
        'PoleRemarks',
        'SDWSizeAsPlan',
        'SDWSizeAsInstalled',
        'SDWLengthAsPlan',
        'SDWLengthAsInstalled',
        'GeoBuilding',
        'GeoTappingPole',
        'GeoMeteringPole',
        'GeoSEPole',
        'FirstNeighborName',
        'FirstNeighborMeterSerial',
        'SecondNeighborName',
        'SecondNeighborMeterSerial',
        'EngineerInchargeName',
        'EngineerInchargeTitle',
        'EngineerInchargeLicenseNo',
        'EngineerInchargeLicenseValidity',
        'EngineerInchargeContactNo',
        'Status',
        'Inspector',
        'DateOfVerification',
        'EstimatedDateForReinspection',
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
        return ServiceConnectionInspections::class;
    }
}
