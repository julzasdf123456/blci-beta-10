<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionPayTransaction
 * @package App\Models
 * @version August 17, 2021, 1:15 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $Particular
 * @property string $Amount
 * @property string $Vat
 * @property string $Total
 */
class ServiceConnectionPayTransaction extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionParticularPaymentsTransactions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;


    public $fillable = [
        'id',
        'ServiceConnectionId',
        'Particular',
        'Amount',
        'Vat', // REMAPED TO NULL, ORNUMBER WHEN PAID
        'Total'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'Particular' => 'string',
        'Amount' => 'string',
        'Vat' => 'string',
        'Total' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'Particular' => 'required|string|max:40',
        'Amount' => 'nullable|string|max:20',
        'Vat' => 'nullable|string|max:100',
        'Total' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
