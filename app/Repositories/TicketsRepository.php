<?php

namespace App\Repositories;

use App\Models\Tickets;
use App\Repositories\BaseRepository;

/**
 * Class TicketsRepository
 * @package App\Repositories
 * @version October 18, 2021, 2:54 pm PST
*/

class TicketsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ConsumerName',
        'Town',
        'Barangay',
        'Sitio',
        'Ticket',
        'Reason',
        'ContactNumber',
        'ReportedBy',
        'ORNumber',
        'ORDate',
        'GeoLocation',
        'Neighbor1',
        'Neighbor2',
        'Notes',
        'Status',
        'DateTimeDownloaded',
        'DateTimeLinemanArrived',
        'DateTimeLinemanExecuted',
        'UserId',
        'CrewAssigned'
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
        return Tickets::class;
    }
}
