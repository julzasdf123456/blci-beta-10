<?php

namespace App\Repositories;

use App\Models\MeterReaderTracks;
use App\Repositories\BaseRepository;

/**
 * Class MeterReaderTracksRepository
 * @package App\Repositories
 * @version December 6, 2021, 4:07 pm PST
*/

class MeterReaderTracksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TrackNameId',
        'Latitude',
        'Longitude'
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
        return MeterReaderTracks::class;
    }
}
