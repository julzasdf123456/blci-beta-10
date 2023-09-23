<?php

namespace App\Repositories;

use App\Models\ORAssigning;
use App\Repositories\BaseRepository;

/**
 * Class ORAssigningRepository
 * @package App\Repositories
 * @version March 24, 2022, 1:45 pm PST
*/

class ORAssigningRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ORNumber',
        'UserId',
        'DateAssigned',
        'IsSetManually',
        'TimeAssigned',
        'Office'
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
        return ORAssigning::class;
    }
}
