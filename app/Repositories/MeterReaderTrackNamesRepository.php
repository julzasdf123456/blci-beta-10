<?php

namespace App\Repositories;

use App\Models\MeterReaderTrackNames;
use App\Repositories\BaseRepository;

/**
 * Class MeterReaderTrackNamesRepository
 * @package App\Repositories
 * @version December 6, 2021, 3:52 pm PST
*/

class MeterReaderTrackNamesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TrackName'
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
        return MeterReaderTrackNames::class;
    }
}
