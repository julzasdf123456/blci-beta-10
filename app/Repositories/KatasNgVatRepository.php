<?php

namespace App\Repositories;

use App\Models\KatasNgVat;
use App\Repositories\BaseRepository;

/**
 * Class KatasNgVatRepository
 * @package App\Repositories
 * @version August 28, 2022, 9:13 am PST
*/

class KatasNgVatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'AccountNumber',
        'Balance',
        'SeriesNo',
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
        return KatasNgVat::class;
    }
}
