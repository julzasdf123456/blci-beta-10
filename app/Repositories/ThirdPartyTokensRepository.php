<?php

namespace App\Repositories;

use App\Models\ThirdPartyTokens;
use App\Repositories\BaseRepository;

/**
 * Class ThirdPartyTokensRepository
 * @package App\Repositories
 * @version September 12, 2022, 2:49 pm PST
*/

class ThirdPartyTokensRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ThirdPartyCompany',
        'ThirdPartyCode',
        'ThirdPartyToken',
        'Status',
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
        return ThirdPartyTokens::class;
    }
}
