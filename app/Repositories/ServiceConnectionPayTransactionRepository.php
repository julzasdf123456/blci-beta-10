<?php

namespace App\Repositories;

use App\Models\ServiceConnectionPayTransaction;
use App\Repositories\BaseRepository;

/**
 * Class ServiceConnectionPayTransactionRepository
 * @package App\Repositories
 * @version August 17, 2021, 1:15 am UTC
*/

class ServiceConnectionPayTransactionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'Particular',
        'Amount',
        'Vat',
        'Total'
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
        return ServiceConnectionPayTransaction::class;
    }
}
