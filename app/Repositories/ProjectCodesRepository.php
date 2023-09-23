<?php

namespace App\Repositories;

use App\Models\ProjectCodes;
use App\Repositories\BaseRepository;

/**
 * Class ProjectCodesRepository
 * @package App\Repositories
 * @version June 27, 2023, 9:33 am PST
*/

class ProjectCodesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ProjectCode',
        'ProjectDescription',
        'ProjectCategory'
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
        return ProjectCodes::class;
    }
}
