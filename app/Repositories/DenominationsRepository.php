<?php

namespace App\Repositories;

use App\Models\Denominations;
use App\Repositories\BaseRepository;

/**
 * Class DenominationsRepository
 * @package App\Repositories
 * @version June 20, 2022, 4:35 pm PST
*/

class DenominationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'AccountNumber',
        'ServicePeriod',
        'OneThousand',
        'FiveHundred',
        'OneHundred',
        'Fifty',
        'Twenty',
        'Ten',
        'Five',
        'Peso',
        'Cents',
        'PaidBillId',
        'Notes',
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
        return Denominations::class;
    }
}
