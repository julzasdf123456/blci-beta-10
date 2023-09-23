<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionTotalPayments
 * @package App\Models
 * @version August 19, 2021, 5:53 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $SubTotal
 * @property string $Form2307TwoPercent
 * @property string $Form2307FivePercent
 * @property string $TotalVat
 * @property string $Total
 * @property string $Notes
 */
class ServiceConnectionTotalPayments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionTotalPayments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;


    public $fillable = [
        'id',
        'ServiceConnectionId',
        'SubTotal',
        'Form2307TwoPercent',
        'Form2307FivePercent',
        'TotalVat',
        'Total',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'SubTotal' => 'string',
        'Form2307TwoPercent' => 'string',
        'Form2307FivePercent' => 'string',
        'TotalVat' => 'string',
        'Total' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'SubTotal' => 'nullable|string|max:60',
        'Form2307TwoPercent' => 'nullable|string|max:60',
        'Form2307FivePercent' => 'nullable|string|max:60',
        'TotalVat' => 'nullable|string|max:60',
        'Total' => 'nullable|string|max:60',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
