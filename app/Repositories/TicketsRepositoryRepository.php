<?php

namespace App\Repositories;

use App\Models\TicketsRepository;
use App\Repositories\BaseRepository;

/**
 * Class TicketsRepositoryRepository
 * @package App\Repositories
 * @version October 19, 2021, 11:58 am PST
*/

class TicketsRepositoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Name',
        'Description',
        'ParentTicket',
        'Type',
        'KPSCategory',
        'KPSIssue'
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
        return TicketsRepository::class;
    }
}
