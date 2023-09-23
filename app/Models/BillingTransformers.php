<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillingTransformers
 * @package App\Models
 * @version November 22, 2021, 11:38 am PST
 *
 * @property string $ServiceAccountId
 * @property string $TransformerNumber
 * @property string $Rating
 * @property string $RentalFee
 * @property string $Load
 */
class BillingTransformers extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_Transformers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceAccountId',
        'TransformerNumber',
        'Rating',
        'RentalFee',
        'Load'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceAccountId' => 'string',
        'TransformerNumber' => 'string',
        'Rating' => 'string',
        'RentalFee' => 'string',
        'Load' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceAccountId' => 'nullable|string|max:120',
        'TransformerNumber' => 'nullable|string|max:120',
        'Rating' => 'nullable|string|max:20',
        'RentalFee' => 'nullable|string|max:30',
        'Load' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
