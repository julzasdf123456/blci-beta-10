<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MemberConsumerSpouse
 * @package App\Models
 * @version July 17, 2021, 1:46 am UTC
 *
 * @property string $MemberConsumerId
 * @property string $FirstName
 * @property string $MiddleName
 * @property string $LastName
 * @property string $Suffix
 * @property string $Gender
 * @property string $Birthdate
 * @property string $Sitio
 * @property string $Barangay
 * @property string $Town
 * @property string $ContactNumbers
 * @property string $EmailAddress
 * @property string $Religion
 * @property string $Citizenship
 * @property string $Notes
 * @property string $Trashed
 */
class MemberConsumerSpouse extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_MemberConsumerSpouse';

    protected $primaryKey = 'id';

    public $incrementing = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'MemberConsumerId' => 'string',
        'FirstName' => 'string',
        'MiddleName' => 'string',
        'LastName' => 'string',
        'Suffix' => 'string',
        'Gender' => 'string',
        'Birthdate' => 'date',
        'Sitio' => 'string',
        'Barangay' => 'string',
        'Town' => 'string',
        'ContactNumbers' => 'string',
        'EmailAddress' => 'string',
        'Religion' => 'string',
        'Citizenship' => 'string',
        'Notes' => 'string',
        'Trashed' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'MemberConsumerId' => 'nullable|string|max:300',
        'FirstName' => 'nullable|string|max:300',
        'MiddleName' => 'nullable|string|max:300',
        'LastName' => 'nullable|string|max:300',
        'Suffix' => 'nullable|string|max:50',
        'Gender' => 'nullable|string|max:50',
        'Birthdate' => 'nullable',
        'Sitio' => 'nullable|string|max:1000',
        'Barangay' => 'nullable|string|max:50',
        'Town' => 'nullable|string|max:50',
        'ContactNumbers' => 'nullable|string|max:300',
        'EmailAddress' => 'nullable|string|max:300',
        'Religion' => 'nullable|string|max:255',
        'Citizenship' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:2000',
        'Trashed' => 'nullable|string|max:5',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
