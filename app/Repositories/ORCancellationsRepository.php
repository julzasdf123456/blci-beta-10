<?php

namespace App\Repositories;

use App\Models\ORCancellations;
use App\Repositories\BaseRepository;

/**
 * Class ORCancellationsRepository
 * @package App\Repositories
 * @version April 1, 2022, 1:11 pm PST
*/

class ORCancellationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ORNumber',
        'ORDate',
        'From',
        'ObjectId',
        'DateTimeFiled',
        'DateTimeApproved'
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
        return ORCancellations::class;
    }
}
