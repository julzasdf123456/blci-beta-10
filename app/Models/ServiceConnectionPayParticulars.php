<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionPayParticulars
 * @package App\Models
 * @version August 17, 2021, 12:43 am UTC
 *
 * @property string $Particular
 * @property string $Description
 * @property string $VatPercentage
 * @property string $Notes
 */
class ServiceConnectionPayParticulars extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionPaymentParticulars';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Particular',
        'DefaultAmount',
        'Description',
        'VatPercentage',
        'Notes',
        'AccountNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Particular' => 'string',
        'DefaultAmount' => 'string',
        'Description' => 'string',
        'VatPercentage' => 'string',
        'Notes' => 'string',
        'AccountNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'Particular' => 'nullable|string|max:500',
        'DefaultAmount' => 'nullable|string',
        'Description' => 'nullable|string|max:800',
        'VatPercentage' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'AccountNumber' => 'nullable|string'
    ];

    
}
