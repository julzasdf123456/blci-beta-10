<?php

namespace App\Repositories;

use App\Models\ServiceAccounts;
use App\Repositories\BaseRepository;

/**
 * Class ServiceAccountsRepository
 * @package App\Repositories
 * @version September 13, 2021, 11:26 am PST
*/

class ServiceAccountsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceAccountName',
        'Town',
        'Barangay',
        'Purok',
        'AccountType',
        'AccountStatus',
        'ContactNumber',
        'EmailAddress',
        'ServiceConnectionId',
        'MeterDetailsId',
        'TransformerDetailsId',
        'PoleNumber',
        'AreaCode',
        'BlockCode',
        'SequenceCode',
        'Feeder',
        'ComputeType',
        'Organization',
        'OrganizationParentAccount',
        'AccountCount',
        'GPSMeter'
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
        return ServiceAccounts::class;
    }
}
