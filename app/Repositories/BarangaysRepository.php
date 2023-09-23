<?php

namespace App\Repositories;

use App\Models\Barangays;
use App\Repositories\BaseRepository;

/**
 * Class BarangaysRepository
 * @package App\Repositories
 * @version July 16, 2021, 9:13 am UTC
*/

class BarangaysRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Barangay',
        'TownId',
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
        return Barangays::class;
    }
}
