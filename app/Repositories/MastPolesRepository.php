<?php

namespace App\Repositories;

use App\Models\MastPoles;
use App\Repositories\BaseRepository;

/**
 * Class MastPolesRepository
 * @package App\Repositories
 * @version June 11, 2022, 6:38 pm PST
*/

class MastPolesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Latitude',
        'Longitude',
        'DateTimeTaken',
        'PoleRemarks'
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
        return MastPoles::class;
    }
}
