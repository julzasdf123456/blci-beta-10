<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AccountGLCodes
 * @package App\Models
 * @version April 25, 2022, 8:31 am PST
 *
 * @property string $AccountCode
 * @property string $NEACode
 * @property string $Status
 * @property string $Notes
 */
class AccountGLCodes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_AccountGLCodes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountCode',
        'NEACode',
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
        'AccountCode' => 'string',
        'NEACode' => 'string',
        'Status' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountCode' => 'nullable|string|max:255',
        'NEACode' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
