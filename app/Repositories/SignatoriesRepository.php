<?php

namespace App\Repositories;

use App\Models\Signatories;
use App\Repositories\BaseRepository;

/**
 * Class SignatoriesRepository
 * @package App\Repositories
 * @version January 8, 2023, 9:06 am PST
*/

class SignatoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Name',
        'Office',
        'Signature',
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
        return Signatories::class;
    }
}
