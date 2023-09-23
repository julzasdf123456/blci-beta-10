<?php

namespace App\Repositories;

use App\Models\AccountLocationHistory;
use App\Repositories\BaseRepository;

/**
 * Class AccountLocationHistoryRepository
 * @package App\Repositories
 * @version June 13, 2022, 8:40 am PST
*/

class AccountLocationHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'Town',
        'Barangay',
        'Purok',
        'AreaCode',
        'SequenceCode',
        'MeterReader',
        'ServiceConnectionId',
        'RelocationDate'
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
        return AccountLocationHistory::class;
    }
}
