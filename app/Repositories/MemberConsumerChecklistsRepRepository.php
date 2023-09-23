<?php

namespace App\Repositories;

use App\Models\MemberConsumerChecklistsRep;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumerChecklistsRepRepository
 * @package App\Repositories
 * @version August 24, 2021, 8:53 am PST
*/

class MemberConsumerChecklistsRepRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Checklist',
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
        return MemberConsumerChecklistsRep::class;
    }
}
