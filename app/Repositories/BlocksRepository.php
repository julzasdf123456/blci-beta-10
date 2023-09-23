<?php

namespace App\Repositories;

use App\Models\Blocks;
use App\Repositories\BaseRepository;

/**
 * Class BlocksRepository
 * @package App\Repositories
 * @version June 30, 2023, 9:47 am PST
*/

class BlocksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Block',
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
        return Blocks::class;
    }
}
