<?php

namespace App\Repositories;

use App\Models\Collectibles;
use App\Repositories\BaseRepository;

/**
 * Class CollectiblesRepository
 * @package App\Repositories
 * @version February 8, 2022, 4:07 pm PST
*/

class CollectiblesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'Balance',
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
        return Collectibles::class;
    }
}
