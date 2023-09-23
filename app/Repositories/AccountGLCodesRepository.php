<?php

namespace App\Repositories;

use App\Models\AccountGLCodes;
use App\Repositories\BaseRepository;

/**
 * Class AccountGLCodesRepository
 * @package App\Repositories
 * @version April 25, 2022, 8:31 am PST
*/

class AccountGLCodesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountCode',
        'NEACode',
        'Status',
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
        return AccountGLCodes::class;
    }
}
