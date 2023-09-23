<?php

namespace App\Repositories;

use App\Models\MemberConsumerImages;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumerImagesRepository
 * @package App\Repositories
 * @version October 11, 2021, 9:51 am PST
*/

class MemberConsumerImagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ConsumerId',
        'PicturePath',
        'HexImage'
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
        return MemberConsumerImages::class;
    }
}
