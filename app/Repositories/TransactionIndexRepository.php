<?php

namespace App\Repositories;

use App\Models\TransactionIndex;
use App\Repositories\BaseRepository;

/**
 * Class TransactionIndexRepository
 * @package App\Repositories
 * @version February 10, 2022, 9:10 am PST
*/

class TransactionIndexRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'TransactionNumber',
        'PaymentTitle',
        'PaymentDetails',
        'ORNumber',
        'ORDate',
        'SubTotal',
        'VAT',
        'Total',
        'Notes',
        'UserId',
        'ServiceConnectionId',
        'TicketId',
        'ObjectId',
        'Source'
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
        return TransactionIndex::class;
    }
}
