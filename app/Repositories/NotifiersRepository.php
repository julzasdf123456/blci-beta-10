<?php

namespace App\Repositories;

use App\Models\Notifiers;
use App\Repositories\BaseRepository;

/**
 * Class NotifiersRepository
 * @package App\Repositories
 * @version April 1, 2022, 11:36 am PST
*/

class NotifiersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Notification',
        'From',
        'To',
        'Status',
        'Intent',
        'IntentLink',
        'ObjectId'
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
        return Notifiers::class;
    }
}
