<?php

namespace App\Repositories;

use App\Models\Notifications;
use App\Repositories\BaseRepository;

/**
 * Class NotificationsRepository
 * @package App\Repositories
 * @version September 4, 2023, 8:24 am PST
*/

class NotificationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'Source',
        'SourceId',
        'ContactNumber',
        'Message',
        'Status',
        'AIFacilitator',
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
        return Notifications::class;
    }
}
