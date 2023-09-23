<?php

namespace App\Repositories;

use App\Models\Zones;
use App\Repositories\BaseRepository;

/**
 * Class ZonesRepository
 * @package App\Repositories
 * @version June 30, 2023, 9:45 am PST
*/

class ZonesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Zone',
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
        return Zones::class;
    }
}
