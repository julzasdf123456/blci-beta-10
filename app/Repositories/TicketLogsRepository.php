<?php

namespace App\Repositories;

use App\Models\TicketLogs;
use App\Repositories\BaseRepository;

/**
 * Class TicketLogsRepository
 * @package App\Repositories
 * @version November 10, 2021, 8:13 am PST
*/

class TicketLogsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TicketId',
        'Log',
        'LogDetails',
        'LogType',
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
        return TicketLogs::class;
    }
}
