<?php

namespace App\Repositories;

use App\Models\AccountNameHistory;
use App\Repositories\BaseRepository;

/**
 * Class AccountNameHistoryRepository
 * @package App\Repositories
 * @version June 11, 2022, 10:48 am PST
*/

class AccountNameHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'OldAccountName',
        'Notes',
        'UserId'
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
        return AccountNameHistory::class;
    }
}
