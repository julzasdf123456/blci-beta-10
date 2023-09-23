<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ReadingImages
 * @package App\Models
 * @version January 31, 2022, 10:39 am PST
 *
 * @property string $Photo
 * @property string $ReadingId
 * @property string $Notes
 */
class ReadingImages extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_ReadingImages';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Photo',
        'ReadingId',
        'Notes',
        'ServicePeriod',
        'AccountNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Photo' => 'string',
        'ReadingId' => 'string',
        'Notes' => 'string',
        'ServicePeriod' => 'string',
        'AccountNumber' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'Photo' => 'nullable|string|max:2500',
        'ReadingId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ServicePeriod' => 'nullable',
        'AccountNumber' => 'nullable|string',
    ];

    
}
