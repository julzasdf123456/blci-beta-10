<?php

namespace App\Repositories;

use App\Models\Events;
use App\Repositories\BaseRepository;

/**
 * Class EventsRepository
 * @package App\Repositories
 * @version November 16, 2022, 7:41 pm PST
*/

class EventsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'EventTitle',
        'EventDescription',
        'EventStart',
        'EventEnd',
        'RegistrationStart',
        'RegistrationEnd',
        'UserId'
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
        return Events::class;
    }
}
