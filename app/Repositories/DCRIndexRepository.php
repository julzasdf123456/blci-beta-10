<?php

namespace App\Repositories;

use App\Models\DCRIndex;
use App\Repositories\BaseRepository;

/**
 * Class DCRIndexRepository
 * @package App\Repositories
 * @version June 12, 2022, 10:57 am PST
*/

class DCRIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'GLCode',
        'NEACode',
        'TableName',
        'Columns'
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
        return DCRIndex::class;
    }
}
