<?php

namespace App\Repositories;

use App\Models\Banks;
use App\Repositories\BaseRepository;

/**
 * Class BanksRepository
 * @package App\Repositories
 * @version May 16, 2022, 1:25 pm PST
*/

class BanksRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'BankFullName',
        'BankAbbrev',
        'Address',
        'TIN'
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
        return Banks::class;
    }
}
