<?php

namespace App\Repositories;

use App\Models\EventAttendees;
use App\Repositories\BaseRepository;

/**
 * Class EventAttendeesRepository
 * @package App\Repositories
 * @version November 16, 2022, 7:42 pm PST
*/

class EventAttendeesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EventId',
        'HaveAttended',
        'AccountNumber',
        'Name',
        'Address',
        'RegisteredAt',
        'RegistationMedium',
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
        return EventAttendees::class;
    }
}
