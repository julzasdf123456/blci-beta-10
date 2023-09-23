<?php

namespace App\Repositories;

use App\Models\MemberConsumerChecklists;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumerChecklistsRepository
 * @package App\Repositories
 * @version August 24, 2021, 8:51 am PST
*/

class MemberConsumerChecklistsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'MemberConsumerId',
        'ChecklistId',
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
        return MemberConsumerChecklists::class;
    }
}
