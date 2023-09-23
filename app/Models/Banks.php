<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Banks
 * @package App\Models
 * @version May 16, 2022, 1:25 pm PST
 *
 * @property string $BankFullName
 * @property string $BankAbbrev
 * @property string $Address
 * @property string $TIN
 */
class Banks extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_Banks';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BankFullName',
        'BankAbbrev',
        'Address',
        'TIN'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BankFullName' => 'string',
        'BankAbbrev' => 'string',
        'Address' => 'string',
        'TIN' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BankFullName' => 'string|max:500',
        'BankAbbrev' => 'string|max:255',
        'Address' => 'nullable|string|max:1000',
        'TIN' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
