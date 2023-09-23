<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ThirdPartyTokens
 * @package App\Models
 * @version September 12, 2022, 2:49 pm PST
 *
 * @property string $ThirdPartyCompany
 * @property string $ThirdPartyCode
 * @property string $ThirdPartyToken
 * @property string $Status
 * @property string $Notes
 */
class ThirdPartyTokens extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'ThirdParty_Tokens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ThirdPartyCompany',
        'ThirdPartyCode',
        'ThirdPartyToken',
        'Status',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ThirdPartyCompany' => 'string',
        'ThirdPartyCode' => 'string',
        'ThirdPartyToken' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ThirdPartyCompany' => 'nullable|string|max:300',
        'ThirdPartyCode' => 'nullable|string|max:100',
        'ThirdPartyToken' => 'required|string|max:600',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
