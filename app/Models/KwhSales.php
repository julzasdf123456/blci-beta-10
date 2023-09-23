<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class KwhSales
 * @package App\Models
 * @version March 28, 2022, 3:31 pm PST
 *
 * @property string $ServicePeriod
 * @property string $Town
 * @property string $BilledKwh
 * @property string $ConsumedKwh
 * @property string $NoOfConsumers
 * @property string $Notes
 */
class KwhSales extends Model
{

    use HasFactory;

    public $table = 'Billing_KwhSales';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServicePeriod',
        'Town',
        'BilledKwh',
        'ConsumedKwh',
        'NoOfConsumers',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServicePeriod' => 'date',
        'Town' => 'string',
        'BilledKwh' => 'string',
        'ConsumedKwh' => 'string',
        'NoOfConsumers' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServicePeriod' => 'nullable',
        'Town' => 'nullable|string|max:255',
        'BilledKwh' => 'nullable|string|max:255',
        'ConsumedKwh' => 'nullable|string|max:255',
        'NoOfConsumers' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
}
