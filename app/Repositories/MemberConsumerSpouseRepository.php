<?php

namespace App\Repositories;

use App\Models\MemberConsumerSpouse;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumerSpouseRepository
 * @package App\Repositories
 * @version July 17, 2021, 1:46 am UTC
*/

class MemberConsumerSpouseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'MemberConsumerId',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Gender',
        'Birthdate',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumbers',
        'EmailAddress',
        'Religion',
        'Citizenship',
        'Notes',
        'Trashed'
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
        return MemberConsumerSpouse::class;
    }
}
