<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AccountPayables
 * @package App\Models
 * @version February 25, 2022, 2:17 pm PST
 *
 * @property string $AccountCode
 * @property string $AccountTitle
 * @property string $AccountDescription
 * @property string $DefaultAmount
 * @property string $VATPercentage
 * @property string $Notes
 */
class AccountPayables extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_AccountPayables';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountCode',
        'AccountTitle',
        'AccountDescription',
        'DefaultAmount',
        'VATPercentage',
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
        'AccountTitle' => 'string',
        'AccountDescription' => 'string',
        'DefaultAmount' => 'string',
        'VATPercentage' => 'string',
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
        'AccountTitle' => 'nullable|string|max:600',
        'AccountDescription' => 'nullable|string|max:700',
        'DefaultAmount' => 'nullable|string|max:255',
        'VATPercentage' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
