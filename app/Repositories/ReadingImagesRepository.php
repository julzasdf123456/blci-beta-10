<?php

namespace App\Repositories;

use App\Models\ReadingImages;
use App\Repositories\BaseRepository;

/**
 * Class ReadingImagesRepository
 * @package App\Repositories
 * @version January 31, 2022, 10:39 am PST
*/

class ReadingImagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Photo',
        'ReadingId',
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
        return ReadingImages::class;
    }
}
