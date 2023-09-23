<?php

namespace App\Repositories;

use App\Models\AccountPayables;
use App\Repositories\BaseRepository;

/**
 * Class AccountPayablesRepository
 * @package App\Repositories
 * @version February 25, 2022, 2:17 pm PST
*/

class AccountPayablesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountCode',
        'AccountTitle',
        'AccountDescription',
        'DefaultAmount',
        'VATPercentage',
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
        return AccountPayables::class;
    }
}
