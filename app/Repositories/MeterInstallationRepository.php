<?php

namespace App\Repositories;

use App\Models\MeterInstallation;
use App\Repositories\BaseRepository;

/**
 * Class MeterInstallationRepository
 * @package App\Repositories
 * @version July 3, 2023, 1:49 pm PST
*/

class MeterInstallationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Type',
        'NewMeterNumber',
        'NewMeterBrand',
        'NewMeterSize',
        'NewMeterType',
        'NewMeterAmperes',
        'NewMeterInitialReading',
        'NewMeterLineToNeutral',
        'NewMeterLineToGround',
        'NewMeterNeutralToGround',
        'DateInstalled',
        'NewMeterMultiplier',
        'TransfomerCapacity',
        'TransformerID',
        'PoleID',
        'CTSerialNumber',
        'NewMeterRemarks',
        'OldMeterNumber',
        'OldMeterBrand',
        'OldMeterSize',
        'OldMeterType',
        'DateRemoved',
        'ReasonForChanging',
        'OldMeterMultiplier',
        'OldMeterRemarks',
        'InstalledBy',
        'CheckedBy',
        'Witness',
        'BLCIRepresentative',
        'ApprovedBy',
        'RemovedBy',
        'CustomerSignature',
        'WitnessSignature',
        'InstalledBySignature',
        'ApprovedBySignature',
        'CheckedBySignature',
        'BLCIRepresentativeSignature'
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
        return MeterInstallation::class;
    }
}
