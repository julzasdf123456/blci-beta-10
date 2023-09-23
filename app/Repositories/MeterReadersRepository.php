<?php

namespace App\Repositories;

use App\Models\MeterReaders;
use App\Repositories\BaseRepository;

/**
 * Class MeterReadersRepository
 * @package App\Repositories
 * @version November 25, 2021, 11:39 am PST
*/

class MeterReadersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'MeterReaderCode',
        'UserId',
        'DeviceMacAddress',
        'AreaCodeAssignment'
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
        return MeterReaders::class;
    }
}
