<?php

namespace App\Repositories;

use App\Models\ServiceConnectionImages;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionImagesRepository
 * @package App\Repositories
 * @version November 17, 2021, 11:38 am PST
*/

class ServiceConnectionImagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Photo',
        'ServiceConnectionId',
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
        return ServiceConnectionImages::class;
    }
}
