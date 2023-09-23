<?php

namespace App\Repositories;

use App\Models\KatasNgVatTotal;
use App\Repositories\BaseRepository;

/**
 * Class KatasNgVatTotalRepository
 * @package App\Repositories
 * @version September 4, 2022, 8:01 am PST
*/

class KatasNgVatTotalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'Balance',
        'SeriesNo',
        'Description',
        'Year',
        'UserId',
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
        return KatasNgVatTotal::class;
    }
}
