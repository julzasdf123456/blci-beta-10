<?php

namespace App\Repositories;

use App\Models\DisconnectionHistory;
use App\Repositories\BaseRepository;

/**
 * Class DisconnectionHistoryRepository
 * @package App\Repositories
 * @version February 21, 2022, 8:54 am PST
*/

class DisconnectionHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'Latitude',
        'Longitude',
        'BillId',
        'DisconnectionPayment',
        'Status',
        'UserId',
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
        return DisconnectionHistory::class;
    }
}
