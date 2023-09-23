<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AccountNameHistory
 * @package App\Models
 * @version June 11, 2022, 10:48 am PST
 *
 * @property string $AccountNumber
 * @property string $OldAccountName
 * @property string $Notes
 * @property string $UserId
 */
class AccountNameHistory extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_AccountNameHistory';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'OldAccountName',
        'Notes',
        'UserId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'OldAccountName' => 'string',
        'Notes' => 'string',
        'UserId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'OldAccountName' => 'nullable|string|max:600',
        'Notes' => 'nullable|string|max:1000',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
