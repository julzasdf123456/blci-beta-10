<?php

namespace App\Repositories;

use App\Models\Towns;
use App\Repositories\BaseRepository;

/**
 * Class TownsRepository
 * @package App\Repositories
 * @version July 16, 2021, 9:12 am UTC
*/

class TownsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Town',
        'District',
        'Station'
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
        return Towns::class;
    }
}
