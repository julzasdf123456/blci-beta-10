<?php

namespace App\Repositories;

use App\Models\MemberConsumers;
use App\Repositories\BaseRepository;

/**
 * Class MemberConsumersRepository
 * @package App\Repositories
 * @version July 16, 2021, 2:02 am UTC
*/

class MemberConsumersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'MembershipType',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'OrganizationName',
        'Birthdate',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumbers',
        'EmailAddress',
        'DateApplied',
        'DateOfPMS',
        'DateApproved',
        'CivilStatus',
        'Religion',
        'Citizenship',
        'ApplicationStatus',
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
        return MemberConsumers::class;
    }
}
