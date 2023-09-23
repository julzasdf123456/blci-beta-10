<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Denominations
 * @package App\Models
 * @version June 20, 2022, 4:35 pm PST
 *
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string $OneThousand
 * @property string $FiveHundred
 * @property string $OneHundred
 * @property string $Fifty
 * @property string $Twenty
 * @property string $Ten
 * @property string $Five
 * @property string $Peso
 * @property string $Cents
 * @property string $PaidBillId
 * @property string $Notes
 * @property string $Total
 */
class Denominations extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_Denominations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ServicePeriod',
        'OneThousand',
        'FiveHundred',
        'OneHundred',
        'Fifty',
        'Twenty',
        'Ten',
        'Five',
        'Peso',
        'Cents',
        'PaidBillId',
        'Notes',
        'Total',
        'ORDate',
        'ORNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'date',
        'OneThousand' => 'string',
        'FiveHundred' => 'string',
        'OneHundred' => 'string',
        'Fifty' => 'string',
        'Twenty' => 'string',
        'Ten' => 'string',
        'Five' => 'string',
        'Peso' => 'string',
        'Cents' => 'string',
        'PaidBillId' => 'string',
        'Notes' => 'string',
        'Total' => 'string',
        'ORDate' => 'string',
        'ORNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'OneThousand' => 'nullable|string|max:255',
        'FiveHundred' => 'nullable|string|max:255',
        'OneHundred' => 'nullable|string|max:255',
        'Fifty' => 'nullable|string|max:255',
        'Twenty' => 'nullable|string|max:255',
        'Ten' => 'nullable|string|max:255',
        'Five' => 'nullable|string|max:255',
        'Peso' => 'nullable|string|max:255',
        'Cents' => 'nullable|string|max:255',
        'PaidBillId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Total' => 'nullable|string|max:50',
        'ORDate' => 'nullable|string',
        'ORNumber' => 'nullable|string'
    ];

    
}
