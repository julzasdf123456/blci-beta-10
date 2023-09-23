<?php

namespace App\Repositories;

use App\Models\DiscoNoticeHistory;
use App\Repositories\BaseRepository;

/**
 * Class DiscoNoticeHistoryRepository
 * @package App\Repositories
 * @version February 21, 2022, 8:55 am PST
*/

class DiscoNoticeHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'BillId'
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
        return DiscoNoticeHistory::class;
    }
}
